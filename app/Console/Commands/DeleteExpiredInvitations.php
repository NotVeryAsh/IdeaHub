<?php

namespace App\Console\Commands;

use App\Models\TeamInvitation;
use Carbon\Carbon;
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
            // Allow expired invitations to stay around for a week after expiration so creators are sure to see that they've expired
            ->where('expires_at', '<=', Carbon::now()->subWeek())
            ->pluck('id');

        $count = TeamInvitation::destroy($teamInvitations);

        $this->info("Deleted {$count} expired invitations.");
    }
}
