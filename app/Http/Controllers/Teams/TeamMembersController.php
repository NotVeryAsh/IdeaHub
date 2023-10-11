<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\View\View;

class TeamMembersController extends Controller
{
    public function index(Team $team): View
    {
        return view('teams.members.index', [
            'team' => $team,
            'members' => $team->members,
            'invitations' => $team->invitations,
        ]);
    }
}
