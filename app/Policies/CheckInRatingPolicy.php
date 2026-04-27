<?php

namespace App\Policies;

use App\Models\CheckInAssignment;
use App\Models\CheckInRating;
use App\Models\User;

class CheckInRatingPolicy
{
    /**
     * Determine if the user can create a rating (requester only).
     */
    public function create(User $user, CheckInAssignment $assignment): bool
    {
        return $user->id === $assignment->checkInRequest->user_id;
    }
}
