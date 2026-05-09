<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Download extends Model
{
    protected $fillable = [
        'school_id', 'category_id', 'title', 'slug', 'description', 'file_path', 'external_url', 'original_filename',
        'mime', 'size_bytes', 'extension', 'download_count', 'preview_image_path', 'is_active',
        'published_at', 'seo_title', 'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DownloadCategory::class, 'category_id');
    }

    public function relatedDownloads(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'download_related',
            'download_id',
            'related_download_id'
        );
    }

    public function scopePublished($query)
    {
        return $query->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeForSchool($query, ?int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function hasFile(): bool
    {
        return $this->file_path !== null && $this->file_path !== '';
    }

    /**
     * Hosted copy on this site, or external URL (e.g. legacy WordPress media).
     */
    public function hasDownloadTarget(): bool
    {
        return $this->hasFile() || filled($this->external_url);
    }

    public function primaryDownloadUrl(): ?string
    {
        if ($this->hasFile()) {
            return route('downloads.file', $this->slug);
        }
        if (filled($this->external_url)) {
            return $this->external_url;
        }

        return null;
    }

    public function primaryDownloadOpensNewTab(): bool
    {
        return ! $this->hasFile() && filled($this->external_url);
    }
}
