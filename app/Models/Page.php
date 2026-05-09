<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    protected $fillable = [
        'school_id', 'title', 'slug', 'excerpt', 'body', 'template', 'published_at',
        'seo_title', 'seo_description', 'seo_keywords', 'og_title', 'canonical_path', 'og_image_path', 'robots',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function publicUrl(): string
    {
        if ($this->school_id) {
            $school = $this->relationLoaded('school') ? $this->school : $this->school()->first();
            if ($school?->slug === 'soc' && $this->slug === 'register') {
                return route('soc.register');
            }
            if ($school) {
                return route('schools.pages.show', [$school, $this->slug]);
            }
        }

        return route('pages.show', $this->slug);
    }
}
