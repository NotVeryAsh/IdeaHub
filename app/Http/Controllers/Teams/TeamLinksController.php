<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamLink;
use App\Models\TeamUser;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class TeamLinksController extends Controller
{
    public function join(Request $request, TeamLink $teamLink)
    {
        if (Carbon::parse($teamLink->expires_at)->isPast()) {
            return view('links.invalid');
        }

        // If user is already logged in
        if ($user = $request->user()) {

            $team = $teamLink->team;

            // Add the user to the team if they are not already a member
            TeamUser::query()->firstOrCreate([
                'team_id' => $team->id,
                'user_id' => $user->id,
            ]);

            if (! $user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')->with([
                    'status' => "You have joined the $team->name team! Now just one final step...",
                ]);
            }

            // Redirect to the team page with a success message
            return redirect()->route('teams.show', $team->id)->with(['status' => "You have joined the $team->name team!"]);
        }

        // Make app redirect to this invitation accept link after logging in or registering
        Session::put('url.intended', $request->getRequestUri());

        // Force user to login before redirecting them the invitation accept link
        return redirect()->route('login');
    }

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
        $url = "http://localhost/teams/1/join/$link->token";

        // Return the url in a json response
        return response()->json([
            'url' => $url,
            'message' => 'Join link copied!',
        ], 201);
    }

    public function show(Team $team): JsonResponse
    {
        $link = $team->link;

        $url = "http://localhost/teams/1/join/$link->token";

        // Return the url in a json response
        return response()->json([
            'url' => $url,
            'message' => 'Join link copied!',
        ]);
    }
}
