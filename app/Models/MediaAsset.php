<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaAsset extends Model
{
    protected $fillable = [
        'user_id', 'school_id', 'disk', 'path', 'original_filename',
        'mime_type', 'size_bytes', 'alt_text',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function publicUrl(): string
    {
        return asset('storage/'.$this->path);
    }

    /** True when the asset can be shown in an <img> (raster or SVG). */
    public function isPreviewableImage(): bool
    {
        $mime = strtolower((string) $this->mime_type);
        if ($mime !== '' && str_starts_with($mime, 'image/')) {
            return true;
        }

        return (bool) preg_match('/\.(jpe?g|png|gif|webp|svg)$/i', $this->path);
    }
}
