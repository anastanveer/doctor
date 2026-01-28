<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RevisionTopic extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'exam_type',
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

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();

        return $this->where($field, $value)
            ->orWhere('id', $value)
            ->firstOrFail();
    }
}
