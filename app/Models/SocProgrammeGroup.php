<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocProgrammeGroup extends Model
{
    protected $fillable = [
        'school_id', 'heading', 'description', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SocProgrammeItem::class, 'soc_programme_group_id')->orderBy('sort_order')->orderBy('id');
    }
}
