<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'location',
        'image',
        'severity',
        'status',
    ];

    /**
     * Get the user who reported this issue.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all votes for this issue.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(IssueVote::class);
    }

    /**
     * Get all fake reports for this issue.
     */
    public function fakeReports(): HasMany
    {
        return $this->hasMany(FakeReport::class);
    }

    /**
     * Get vote count for this issue.
     */
    public function getVoteCountAttribute(): int
    {
        return $this->votes()->count();
    }
}
