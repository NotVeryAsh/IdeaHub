<?php

namespace App\Console\Commands;

use App\Models\TeamLink;
use Illuminate\Console\Command;

class DeleteExpiredLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'links:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired links';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Deleting expired links...');

        $teamLinks = TeamLink::query()
            ->expired()
            ->pluck('id');

        $count = TeamLink::destroy($teamLinks);

        $this->info("Deleted {$count} expired links.");
    }
}
