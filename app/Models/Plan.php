<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    public const EXAM_PRIMARY = 'primary';
    public const EXAM_INTERMEDIATE = 'intermediate';
    public const EXAM_TYPES = [
        self::EXAM_PRIMARY,
        self::EXAM_INTERMEDIATE,
    ];

    protected $fillable = [
        'name',
        'exam_type',
        'duration_months',
        'price_cents',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_months' => 'integer',
        'price_cents' => 'integer',
    ];

    public function examLabel(): string
    {
        return $this->exam_type === self::EXAM_INTERMEDIATE
            ? 'MRCEM Intermediate'
            : 'MRCEM Primary';
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
