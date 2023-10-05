<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teams\StoreTeamInvitationRequest;
use App\Mail\Invitations\TeamInvitationSent;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class TeamInvitationsController extends Controller
{
    public function store(StoreTeamInvitationRequest $request, Team $team): RedirectResponse
    {
        // Get the recipient email from the request
        $recipient = $request->validated('email');

        // Create a signed url for the team invitation which will expire after a week
        $url = URL::temporarySignedRoute('invitations.accept', now()->addWeek(), ['email' => $recipient, 'team' => $team]);

        // Send a mailable to the recipient with the signed url and the team
        Mail::to($request)->queue(new TeamInvitationSent($team, $url));

        // Redirect back to the team page with a success message
        return redirect()->route('teams.show', $team)->with(['status' => 'Invitation sent!']);
    }
}
