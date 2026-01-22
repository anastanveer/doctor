<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MockPaperOption extends Model
{
    protected $fillable = [
        'mock_paper_question_id',
        'text',
        'is_correct',
        'order',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(MockPaperQuestion::class, 'mock_paper_question_id');
    }
}
