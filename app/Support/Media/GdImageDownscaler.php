<?php

namespace App\Support\Media;

final class GdImageDownscaler
{
    public static function downscaleMaxWidth(string $absolutePath, int $maxWidth = 2400): bool
    {
        if (! extension_loaded('gd') || ! is_file($absolutePath)) {
            return false;
        }

        $info = @getimagesize($absolutePath);
        if ($info === false) {
            return false;
        }

        [$width, $height, $type] = $info;
        if ($width <= $maxWidth) {
            return false;
        }

        $newW = $maxWidth;
        $newH = (int) max(1, round($height * ($maxWidth / $width)));

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($absolutePath),
            IMAGETYPE_PNG => @imagecreatefrompng($absolutePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($absolutePath) : false,
            default => false,
        };

        if ($src === false) {
            return false;
        }

        $dst = imagecreatetruecolor($newW, $newH);
        if ($dst === false) {
            imagedestroy($src);

            return false;
        }

        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
        imagedestroy($src);

        $ok = match ($type) {
            IMAGETYPE_JPEG => imagejpeg($dst, $absolutePath, 90),
            IMAGETYPE_PNG => imagepng($dst, $absolutePath, 6),
            IMAGETYPE_WEBP => function_exists('imagewebp') ? imagewebp($dst, $absolutePath, 90) : false,
            default => false,
        };
        imagedestroy($dst);

        return (bool) $ok;
    }
}
