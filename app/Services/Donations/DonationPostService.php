<?php

namespace App\Services\Donations;

use App\Models\DonationPost;
use App\Models\User;

class DonationPostService
{
    public function create(array $data, User $user): DonationPost
    {
        return DonationPost::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'goal_amount' => $data['goal_amount'],
            'raised_amount' => 0,
            'status' => 'active',
            'approval_status' => 'pending',
            'category' => $data['category'],
            'location' => $data['location'] ?? null,
            'proof_files' => $data['proof_files'] ?? null,
        ]);
    }

    public function approve(DonationPost $post, int $reviewerId, ?string $note = null): DonationPost
    {
        $post->update([
            'approval_status' => 'approved',
            'admin_note' => $note,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);

        return $post->refresh();
    }

    public function reject(DonationPost $post, int $reviewerId, string $note): DonationPost
    {
        $post->update([
            'approval_status' => 'rejected',
            'admin_note' => $note,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);

        return $post->refresh();
    }
}
