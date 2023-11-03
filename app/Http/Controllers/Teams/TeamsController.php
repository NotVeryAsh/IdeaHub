<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\StoreTeamRequest;
use App\Http\Requests\Teams\UpdateTeamRequest;
use App\Mail\Teams\TeamCreated;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamLink;
use App\Models\TeamUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class TeamsController extends Controller
{
    public function index(): View
    {
        $user = request()->user();

        // Get teams that the user owns
        $ownedTeams = $user->ownedTeams()
            // with soft deleted teams
            ->withTrashed()
            // Count the amount of members in the team
            ->withCount('members')
            // with the creator of the team
            ->with('creator')
            // Show deleted teams last
            ->orderBy('deleted_at', 'asc')
            ->get();

        // Get teams that the user is a member of
        $teams = $user->teams()
            // with soft deleted teams
            ->withTrashed()
            // Count the amount of members in the team
            ->withCount('members')
            // with the creator of the team
            ->with('creator')
            ->get();

        // Get all teams - including soft deleted teams, with their creators and amount of members
        return view('teams.index', [
            'ownedTeams' => $ownedTeams,
            'teams' => $teams,
        ]);
    }

    public function show(Team $team): View
    {
        return view('teams.show', [
            'team' => $team,
        ]);
    }

    public function store(StoreTeamRequest $request): RedirectResponse
    {
        $user = request()->user();

        // Create the team
        $team = Team::query()->create([
            'name' => $request->validated('name'),
            'creator_id' => $user->id,
        ]);

        // Send confirmation that the team was created
        Mail::to($user->email)->queue(new TeamCreated($user, $team));

        return redirect()->route('teams.show', $team)->with(['status' => 'Team created successfully!']);
    }

    public function update(UpdateTeamRequest $request, Team $team): RedirectResponse
    {
        $team->update([
            'name' => $request->validated('name'),
        ]);

        return redirect()->route('teams.show', $team)->with(['status' => 'Team updated successfully!']);
    }

    public function delete(Team $team): RedirectResponse
    {
        DB::transaction(function () use ($team) {

            // Remove links and invitations for team
            TeamLink::query()->where('team_id', $team->id)->delete();
            TeamInvitation::query()->where('team_id', $team->id)->delete();

            // If we are hard deleting the team, remove all the members and delete the team
            if ($team->trashed()) {

                // Remove team members from the team
                TeamUser::query()->where('team_id', $team->id)->delete();
                $team->forceDelete();

                return;
            }

            $team->delete();
        });

        return redirect()->route('teams.index')->with(['status' => 'Team deleted successfully!']);
    }

    public function restore(Team $team): RedirectResponse
    {
        $team->restore();

        return redirect()->route('teams.index')->with(['status' => 'Team restored successfully!']);
    }
}
