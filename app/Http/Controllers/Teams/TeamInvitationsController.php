<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\StoreTeamInvitationRequest;
use App\Mail\Invitations\TeamInvitationSent;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
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

        // TODO Delete old invitations if a new one is created for the same email - and make a test for this

        // Create a signed url for the team invitation which will expire after a week
        $url = URL::temporarySignedRoute('invitations.accept', $expiresAt, ['token' => $invitation->token]);

        // Send a mailable to the recipient with the signed url and the team
        Mail::to($recipient)->queue(new TeamInvitationSent($team, $url));

        // Redirect back to the team page with a success message
        return redirect()->route('teams.members', $team)->with(['status' => 'Invitation sent!']);
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

            $team = $invitation->team;

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

        // Get data for login and register routes
        $data = [
            'token' => $token,
        ];

        // If a user with the invitation email has already signed up
        if (User::query()->where('email', $invitation->email)->first()) {

            // Force user to login before redirecting them the invitation accept link
            return redirect()->route('login', $data);
        }

        // Force user to sign up before redirecting them the invitation accept link
        return redirect()->route('register', $data);
    }

    public function delete(TeamInvitation $teamInvitation): RedirectResponse
    {
        // Get the team from the invitation
        $team = $teamInvitation->team;

        // Delete the invitation
        $teamInvitation->delete();

        // Redirect back to the team page with a success message
        return redirect()->route('teams.members', ['team' => $team])->with(['status' => 'Invitation deleted!']);
    }
}
