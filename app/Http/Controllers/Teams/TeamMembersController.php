<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\ListTeamMembersRequest;
use App\Models\Team;
use App\Models\User;
use App\Services\TeamMemberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeamMembersController extends Controller
{
    public function index(ListTeamMembersRequest $request, Team $team): View
    {
        $members = TeamMemberService::filter($request, $team);

        return view('teams.members.index', [
            'creator' => $team->creator,
            'team' => $team,
            'members' => $members,
            'invitations' => $team->invitations,
            'orderBy' => $request->validated('order_by', 'name'),
            'orderByDirection' => $request->validated('order_by_direction', 'asc'),
        ]);
    }

    public function remove(Team $team, User $member): RedirectResponse
    {
        // If member isn't in team
        if (!$team->members->contains($member)){
            abort(404);
        }

        // Remove the member from the team
        $team->members()->detach($member);

        // Redirect back to the team page with a success message
        return redirect()->route('teams.members', $team)->with(['status' => 'Team member removed!']);
    }

    public function leave(Team $team): RedirectResponse
    {
        $user = request()->user();

        // Remove the authenticated user from the team
        $team->members()->detach($user);

        // Redirect back to the team page with a success message
        return redirect()->route('teams.index', $team)->with(['status' => 'You have left the team!']);
    }
}
