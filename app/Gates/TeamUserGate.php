<?php

namespace App\Gates;

use App\Models\Team;
use App\Models\User;

class TeamUserGate
{
    public function viewAny(Team $team, User $user): bool
    {
        return $team->members->contains($user);
    }
}
