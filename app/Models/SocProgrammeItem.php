<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocProgrammeItem extends Model
{
    protected $fillable = [
        'school_id', 'soc_programme_group_id', 'slug', 'title', 'badge', 'summary', 'body',
        'seo_title', 'seo_description', 'seo_keywords', 'og_title', 'og_image_path',
        'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(SocProgrammeGroup::class, 'soc_programme_group_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
