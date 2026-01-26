<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MockPaper extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'exam_type',
        'description',
        'duration_minutes',
        'order',
        'is_active',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(MockPaperQuestion::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
