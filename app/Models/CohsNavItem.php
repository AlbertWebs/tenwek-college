<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CohsNavItem extends Model
{
    protected $fillable = [
        'school_id', 'parent_id', 'mega_id', 'label', 'page_slug', 'route_name',
        'external_url', 'external_config_key', 'open_new_tab', 'is_highlight', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'open_new_tab' => 'boolean',
            'is_highlight' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order')->orderBy('id');
    }
}
