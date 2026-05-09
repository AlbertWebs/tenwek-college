<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteAdminSetting extends Model
{
    protected $table = 'site_admin_settings';

    protected $fillable = [
        'general',
        'global_seo',
    ];

    protected function casts(): array
    {
        return [
            'general' => 'array',
            'global_seo' => 'array',
        ];
    }

    public static function instance(): self
    {
        $row = static::query()->orderBy('id')->first();
        if ($row !== null) {
            return $row;
        }

        return static::query()->create([
            'general' => [],
            'global_seo' => [],
        ]);
    }
}
