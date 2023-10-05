<?php

namespace App\Mail\Invitations;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamInvitationSent extends Mailable
{
    use Queueable, SerializesModels;

    public Team $team;

    public string $url;

    /**
     * Create a new message instance.
     */
    public function __construct($team, $url)
    {
        $this->team = $team;
        $this->url = $url;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: "You have been invited to join the {$this->team->name} team!",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invitations.sent',
            with: [
                'team' => $this->team,
                'url' => $this->url,
            ],
        );
    }
}
