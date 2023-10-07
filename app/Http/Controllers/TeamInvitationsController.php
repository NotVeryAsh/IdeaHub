<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teams\StoreTeamInvitationRequest;
use App\Mail\Invitations\TeamInvitationSent;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        // If user is already logged in
        if ($user = $request->user()) {

            // If user's email and invitation email do not match
            if (strtolower($invitation->email) !== strtolower($user->email)) {
                return view('invitations.invalid');
            }

            // Delete all invitations for this user to the team
            TeamInvitation::query()
                ->where([
                    'team_id' => $invitation->team_id,
                    'email' => $user->email,
                ])
                ->delete();

            // Add the user to the team if they are not already a member
            TeamUser::query()->firstOrCreate([
                'team_id' => $invitation->team_id,
                'user_id' => $user->id,
            ]);

            // Redirect to the team page with a success message
            return redirect()->route('teams.show', $invitation->team)->with(['status' => 'Invitation accepted!']);
        }

        // If a user with the invitation email has already signed up
        if ($user = User::query()->where('email', $invitation->email)->first()) {

            // Force user to login before redirecting them the invitation accept link
            return redirect()->route('login', [
                'email' => $user->email,
                'redirect' => $request->getRequestUri(),
            ]);
        }

        // Force user to sign up before redirecting them the invitation accept link
        return redirect()->route('signup', [
            'email' => $user->email,
            'redirect' => $request->getRequestUri(),
        ]);
    }
}
