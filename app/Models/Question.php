<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'topic_id',
        'exam_type',
        'type',
        'difficulty',
        'stem',
        'explanation',
        'answer_text',
        'is_active',
        'shuffle_options',
        'time_limit',
        'meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'shuffle_options' => 'boolean',
        'meta' => 'array',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class)->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuestionAttempt::class);
    }
}
