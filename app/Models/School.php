<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'name', 'slug', 'tagline', 'excerpt', 'body', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }

    public function newsPosts(): HasMany
    {
        return $this->hasMany(NewsPost::class);
    }

    public function schoolEvents(): HasMany
    {
        return $this->hasMany(SchoolEvent::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function downloadCategories(): HasMany
    {
        return $this->hasMany(DownloadCategory::class);
    }

    public function socLandingSections(): HasMany
    {
        return $this->hasMany(SocLandingSection::class);
    }

    public function socTestimonials(): HasMany
    {
        return $this->hasMany(SocTestimonial::class);
    }

    public function socNavItems(): HasMany
    {
        return $this->hasMany(SocNavItem::class);
    }

    public function socTeamMembers(): HasMany
    {
        return $this->hasMany(SocTeamMember::class);
    }

    public function socProgrammeGroups(): HasMany
    {
        return $this->hasMany(SocProgrammeGroup::class);
    }
}
