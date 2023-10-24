<?php

namespace App\Gates;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamUserGate
{
    public static function viewAny(User $user, Team $team): bool
    {
        return $team->members->contains(Auth::user());
    }
}
