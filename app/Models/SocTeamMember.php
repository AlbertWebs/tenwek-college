<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocTeamMember extends Model
{
    public const TEAM_BOARD = 'board';

    public const TEAM_MANAGEMENT = 'management';

    public const TEAM_FACULTY = 'faculty';

    protected $fillable = [
        'school_id', 'team', 'name', 'role_title', 'bio', 'image_path',
        'highlight', 'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'highlight' => 'boolean',
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

    public function scopeTeam($query, string $team)
    {
        return $query->where('team', $team);
    }
}
