<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamInvitationPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Team $team): bool
    {
        return $user->is($team->creator);
    }
}
