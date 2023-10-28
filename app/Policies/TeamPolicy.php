<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    public function view(User $user, Team $team): Response
    {
        // Check if user is a member of the team or the creator of the team
        $canAccess = $team->members->contains($user->id) ||
            $team->creator->is($user);

        // Check if user is a member of the team
        return $canAccess ?
            Response::allow() :
            Response::denyWithStatus(404);
    }

    public function update(User $user, Team $team): Response
    {
        // Check if user is the creator of the team
        return $team->creator->is($user) ?
            Response::allow() :
            Response::denyWithStatus(404);
    }

    public function delete(User $user, Team $team): Response
    {
        // Check if user is the creator of the team
        return $team->creator->is($user) ?
            Response::allow() :
            Response::denyWithStatus(404);
    }

    public function restore(User $user, Team $team): Response
    {
        // Check if user is the creator of the team
        return $team->creator->is($user) ?
            Response::allow() :
            Response::denyWithStatus(404);
    }
}
