<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class TeamLinksController extends Controller
{
    public function store(Team $team): JsonResponse
    {
        $expiresAt = now()->addMonth();

        // Delete old team links
        $team->link()->delete();

        // Create a join link for the team which expires in a month
        $link = TeamLink::query()->create([
            'token' => Str::random(32),
            'team_id' => $team->id,
            'expires_at' => $expiresAt,
        ]);

        // Generate a url with the link's token and the team eg. localhost:8000/teams/1/join/$link->token
        // TODO Update this url
        //$url = route('links.join', [$team, $link->token]);
        $url = "http://localhost:8000/teams/1/join/$link->token";

        // Return the url in a json response
        return response()->json(['url' => $url], 201);
    }
}
