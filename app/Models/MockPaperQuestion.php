<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MockPaperQuestion extends Model
{
    protected $fillable = [
        'mock_paper_id',
        'topic',
        'stem',
        'explanation',
        'order',
    ];

    public function paper(): BelongsTo
    {
        return $this->belongsTo(MockPaper::class, 'mock_paper_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(MockPaperOption::class);
    }
}
