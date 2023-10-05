<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teams\StoreTeamInvitationRequest;
use App\Mail\Invitations\TeamInvitationSent;
use App\Models\Team;
use App\Models\TeamInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class TeamInvitationsController extends Controller
{
    public function store(StoreTeamInvitationRequest $request, Team $team): RedirectResponse
    {
        // Get the recipient email from the request
        $recipient = $request->validated('email');
        $expiresAt = now()->addWeek();

        $invitation = TeamInvitation::query()->create([
            'token' => Str::random(32),
            'team_id' => $team->id,
            'email' => $recipient,
            'expires_at' => $expiresAt,
        ]);

        // Create a signed url for the team invitation which will expire after a week
        $url = URL::temporarySignedRoute('invitations.accept', $expiresAt, ['token' => $invitation->token]);

        // Send a mailable to the recipient with the signed url and the team
        Mail::to($request)->queue(new TeamInvitationSent($team, $url));

        // Redirect back to the team page with a success message
        return redirect()->route('teams.show', $team)->with(['status' => 'Invitation sent!']);
    }

    public function accept($email, $team)
    {

    }
}
