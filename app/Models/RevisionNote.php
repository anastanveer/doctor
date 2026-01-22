<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevisionNote extends Model
{
    protected $fillable = [
        'revision_topic_id',
        'title',
        'slug',
        'summary',
        'content',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(RevisionTopic::class, 'revision_topic_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
