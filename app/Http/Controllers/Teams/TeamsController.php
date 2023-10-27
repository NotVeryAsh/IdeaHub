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
        return view('teams.index', [
            'ownedTeams' => request()->user()->ownedTeams,
            'teams' => request()->user()->teams,
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
}
