<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolEvent extends Model
{
    protected $fillable = [
        'school_id', 'author_id', 'title', 'slug', 'excerpt', 'body', 'image_path',
        'starts_at', 'ends_at', 'location', 'registration_url',
        'published_at', 'seo_title', 'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /**
     * Public URL for uploaded images (public disk) or legacy public/ paths.
     */
    public function imagePublicUrl(): ?string
    {
        $path = $this->image_path;
        if ($path === null || $path === '') {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, 'soc/') || str_starts_with($path, 'cohs/')) {
            return asset('storage/'.$path);
        }

        return asset($path);
    }
}
