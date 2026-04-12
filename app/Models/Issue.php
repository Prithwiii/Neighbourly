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

        // Only adjust status if the issue isn't already verified. A verified
        // report should not be downgraded by user-driven fake reports.
        if ($this->status === 'verified') {
            return;
        }

        if ($fakeReportsCount >= 5) {
            // once threshold reached mark as flagged, no matter previous state
            if ($this->status !== 'flagged') {
                $this->update(['status' => 'flagged']);
            }
        } else {
            // if it was previously flagged but reports dipped below threshold,
            // revert back to under_review so admins can re-evaluate.
            if ($this->status === 'flagged') {
                $this->update(['status' => 'under_review']);
            }

            // otherwise leave status as-is (under_review or open/resolved etc.)
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
