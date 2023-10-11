<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\StoreTeamInvitationRequest;
use App\Mail\Invitations\TeamInvitationSent;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
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
