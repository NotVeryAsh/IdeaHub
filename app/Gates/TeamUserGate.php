<?php

namespace App\Gates;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamUserGate
{
    public static function viewAny(User $user, Team $team): Response
    {
        // Check if user is the creator of the team of in the team
        return $user->is($team->creator) || $team->members->contains($user) ?
            Response::allow() :
            Response::denyWithStatus(404);
    }

    public static function delete(User $creator, Team $team, User $member): Response
    {
        $userIsCreator = $creator->is($team->creator);

        // Check if user is the creator of the team
        return $userIsCreator ?
            Response::allow() :
            Response::denyWithStatus(404);
    }

    public static function leave(User $user, Team $team): Response
    {
        // Check if user is a member of the team
        $canAccess = $team->members->contains($user);

        return $canAccess ?
            Response::allow() :
            Response::denyWithStatus(404);
    }
}
