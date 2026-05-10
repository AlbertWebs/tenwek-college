<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocFaqItem extends Model
{
    protected $fillable = [
        'school_id', 'sort_order', 'question', 'payload',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Shape expected by resources/views/schools/soc/faqs.blade.php.
     *
     * @return array<string, mixed>
     */
    public function toLegacyItemArray(): array
    {
        $item = ['question' => $this->question];
        $payload = is_array($this->payload) ? $this->payload : [];

        return array_merge($item, $payload);
    }
}
