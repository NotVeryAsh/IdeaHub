<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\StoreTeamRequest;
use App\Http\Requests\Teams\UpdateTeamRequest;
use App\Mail\Teams\TeamCreated;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class TeamsController extends Controller
{
    public function index(): View
    {
        // Get all teams - including soft deleted teams, with their creators and amount of members
        return view('teams.index', [
            'ownedTeams' => request()->user()->ownedTeams()->withTrashed()->withCount('members')->with('creator')->orderBy('deleted_at', 'asc')->get(),
            'teams' => request()->user()->teams()->withTrashed()->withCount('members')->with('creator')->get(),
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
        $team->delete();

        return redirect()->route('teams.index')->with(['status' => 'Team deleted successfully!']);
    }

    public function restore(Team $team): RedirectResponse
    {
        $team->restore();

        return redirect()->route('teams.index')->with(['status' => 'Team restored successfully!']);
    }

    // TODO Add method to hard delete a team

    // TODO Add method to allow users to leave a team
}
