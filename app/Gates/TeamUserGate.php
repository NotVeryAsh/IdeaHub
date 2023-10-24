<?php

namespace App\Gates;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class TeamUserGate
{
    public static function viewAny(User $user, Team $team): Response
    {
        // Check if user is the creator of the team
        return $user->is($team->creator) || $team->members->contains(Auth::user()) ?
            Response::allow() :
            Response::denyWithStatus(404);
    }
}
