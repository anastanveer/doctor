<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RevisionTopic extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function notes(): HasMany
    {
        return $this->hasMany(RevisionNote::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
