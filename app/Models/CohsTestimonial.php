<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CohsTestimonial extends Model
{
    protected $fillable = [
        'school_id', 'name', 'designation', 'organization', 'quote',
        'image_path', 'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
