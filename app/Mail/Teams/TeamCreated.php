<?php

namespace App\Mail\Teams;

use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamCreated extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public Team $team;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $team)
    {
        $this->user = $user;
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
