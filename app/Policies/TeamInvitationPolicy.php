<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamInvitationPolicy
{
    public function create(User $user, Team $team): Response
    {
        // Check if user is the creator of the team
        return $user->is($team->creator) ?
            Response::allow() :
            Response::denyWithStatus(404);
    }

    public function delete(User $user, TeamInvitation $invitation): Response
    {
        // Check if user is the creator of the team
        return $user->is($invitation->team->creator) ?
            Response::allow() :
            Response::denyWithStatus(404);
    }
}
