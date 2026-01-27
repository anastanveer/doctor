<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MockPaperProgress extends Model
{
    protected $fillable = [
        'user_id',
        'mock_paper_id',
        'active_index',
        'state',
    ];

    protected $casts = [
        'active_index' => 'integer',
        'state' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paper(): BelongsTo
    {
        return $this->belongsTo(MockPaper::class, 'mock_paper_id');
    }
}
