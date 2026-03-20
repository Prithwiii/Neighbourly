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

    protected $attributes = [
        'status' => 'under_review',
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

    /**
     * Check if a user has voted on this issue.
     */
    public function hasUserVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    /**
     * Check if a user has reported this issue as fake.
     */
    public function hasUserReported($userId)
    {
        return $this->fakeReports()->where('user_id', $userId)->exists();
    }

    /**
     * Update status based on fake reports count.
     */
    public function updateStatusBasedOnReports()
    {
        $fakeReportsCount = $this->fakeReports()->count();

        if ($fakeReportsCount >= 5) {
            $this->update(['status' => 'flagged']);
        } elseif ($this->status === 'under_review' && $fakeReportsCount < 5) {
            // If it's been under review and hasn't reached 5 fake reports,
            // we could implement a time-based verification here
            // For now, we'll keep it under review until manually verified
        }
    }

    /**
     * Mark issue as verified.
     */
    public function markAsVerified()
    {
        $this->update(['status' => 'verified']);
    }

    /**
     * Get status badge information.
     */
    public function getStatusBadge()
    {
        return match($this->status) {
            'verified' => ['text' => '✅ Verified', 'class' => 'verified'],
            'under_review' => ['text' => '🟡 Under Review', 'class' => 'under-review'],
            'flagged' => ['text' => '🚩 Flagged', 'class' => 'flagged'],
            default => ['text' => '❓ Unknown', 'class' => 'unknown'],
        };
    }
}
