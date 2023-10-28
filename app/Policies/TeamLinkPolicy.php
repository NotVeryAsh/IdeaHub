<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamLinkPolicy
{
    public function create(User $user, Team $team): Response
    {
        // Check if user is the creator of the team
        return $user->is($team->creator) ?
            Response::allow() :
            Response::denyWithStatus(404);
    }

    public function view(User $user, Team $team): Response
    {
        // Check if user is the creator of the team
        return $user->is($team->creator) ?
            Response::allow() :
            Response::denyWithStatus(404);
    }
}
