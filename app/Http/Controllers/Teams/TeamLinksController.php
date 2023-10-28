<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class TeamLinksController extends Controller
{
    public function store(Team $team): RedirectResponse
    {
        $expiresAt = now()->addMonth();

        // Delete old team links
        $team->link()->delete();

        // Create a join link for the team which expires in a month
        TeamLink::query()->create([
            'token' => Str::random(32),
            'team_id' => $team->id,
            'expires_at' => $expiresAt,
        ]);

        // Redirect back to the team page with a success message
        return redirect()->route('teams.members', $team)->with(['status' => 'Join link created!']);
    }
}
