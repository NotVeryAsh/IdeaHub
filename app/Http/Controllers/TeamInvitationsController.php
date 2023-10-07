<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teams\StoreTeamInvitationRequest;
use App\Mail\Invitations\TeamInvitationSent;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
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
        Mail::to($recipient)->queue(new TeamInvitationSent($team, $url));

        // Redirect back to the team page with a success message
        return redirect()->route('teams.show', $team)->with(['status' => 'Invitation sent!']);
    }

    public function accept(Request $request, $token)
    {
        if (! $request->hasValidSignature()) {
            return view('invitations.invalid');
        }

        if (! $invitation = TeamInvitation::query()->where('token', $token)->first()) {
            return view('invitations.invalid');
        }

        // if user is logged in
        // if user's email is the same as the invitation email
        // delete the invitation and redirect to teams/{team} with a success message
        // else if user's email is not the same, redirect user to the invalid invitation page

        // if a user exists with the invitation emails
        // redirect user to the login page with a redirect parameter back to this route
        // else redirect user to the signup page with a redirect parameter back to this route
    }
}
