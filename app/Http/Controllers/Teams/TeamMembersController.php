<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\ListTeamMembersRequest;
use App\Models\Team;
use App\Services\TeamMemberService;
use Illuminate\View\View;

class TeamMembersController extends Controller
{
    public function index(ListTeamMembersRequest $request, Team $team): View
    {
        $members = TeamMemberService::filter($request, $team);

        return view('teams.members.index', [
            'team' => $team,
            'members' => $members,
            'invitations' => $team->invitations,
            'orderBy' => $request->validated('order_by', 'name'),
            'orderByDirection' => $request->validated('order_by_direction', 'asc'),
        ]);
    }
}
