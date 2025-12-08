<?php

namespace App\Console\Commands;

use App\Services\EpgReminderService;
use Illuminate\Console\Command;

class CleanupEpgReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epg:cleanup-reminders {--days=30 : Number of days to keep old reminders}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old sent EPG reminders';

    /**
     * Execute the console command.
     */
    public function handle(EpgReminderService $reminderService): int
    {
        $days = (int) $this->option('days');
        
        $this->info("Cleaning up reminders older than {$days} days...");
        
        $deleted = $reminderService->cleanupOldReminders($days);
        
        $this->info("Deleted {$deleted} old reminders");
        
        return Command::SUCCESS;
    }
}
