<?php

namespace App\Policies;

use App\Models\CheckInAssignment;
use App\Models\CheckInRating;
use App\Models\User;

class CheckInAssignmentPolicy
{
    /**
     * Determine if the user can confirm the assignment (requester only).
     */
    public function confirm(User $user, CheckInAssignment $assignment): bool
    {
        return $user->id === $assignment->checkInRequest->user_id;
    }

    /**
     * Determine if the user can cancel the assignment (requester only).
     */
    public function cancel(User $user, CheckInAssignment $assignment): bool
    {
        return $user->id === $assignment->checkInRequest->user_id;
    }
}
