<?php

namespace App\Console\Commands;

use App\Models\TeamInvitation;
use Illuminate\Console\Command;

class DeleteExpiredInvitations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invitations:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired invitations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Deleting expired invitations...');

        $teamInvitations = TeamInvitation::query()
            ->expired()
            ->pluck('id');

        $count = TeamInvitation::destroy($teamInvitations);

        $this->info("Deleted {$count} expired invitations.");
    }
}
