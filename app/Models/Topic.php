<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    public const EXAM_PRIMARY = 'primary';
    public const EXAM_INTERMEDIATE = 'intermediate';
    public const EXAM_TYPES = [
        self::EXAM_PRIMARY,
        self::EXAM_INTERMEDIATE,
    ];

    protected $fillable = [
        'name',
        'slug',
        'exam_type',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
