<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamUserPolicy
{
    public function viewAny(User $user, Team $team): Response
    {
        // Check if user is a member of the team or the creator of the team
        $canAccess = $team->members->contains($user->id) ||
            $team->creator->is($user);

        // Check if user is a member of the team
        return $canAccess ?
            Response::allow() :
            Response::denyWithStatus(404);
    }
}
