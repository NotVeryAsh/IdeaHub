<?php

namespace App\Mail\Invitations;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationSent extends Mailable
{
    use Queueable, SerializesModels;

    public String $recipientEmail;

    public Team $team;

    /**
     * Create a new message instance.
     */
    public function __construct($recipientEmail, $team)
    {
        $this->$recipientEmail = $recipientEmail;
        $this->team = $team;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: "{$this->team->name} Team Created!",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    { // TODO Change members page url
        return new Content(
            markdown: 'emails.teams.created',
            with: [
                'user' => $this->user,
                'team' => $this->team,
                'membersPageURL' => /*route('teams.members', $this->team)*/ config('app.url')."/teams/{$this->team->id}/members",
            ],
        );
    }
}
