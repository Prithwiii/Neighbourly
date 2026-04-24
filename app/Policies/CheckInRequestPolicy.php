<?php

namespace App\Policies;

use App\Models\CheckInRequest;
use App\Models\User;

class CheckInRequestPolicy
{
    /**
     * Determine if the user can update the request (requester only).
     */
    public function update(User $user, CheckInRequest $request): bool
    {
        return $user->id === $request->user_id;
    }
}
