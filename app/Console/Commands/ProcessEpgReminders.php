<?php

namespace App\Console\Commands;

use App\Services\EpgReminderService;
use Illuminate\Console\Command;

class ProcessEpgReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epg:process-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process due EPG reminders and send notifications';

    /**
     * Execute the console command.
     */
    public function handle(EpgReminderService $reminderService): int
    {
        $this->info('Processing EPG reminders...');
        
        $results = $reminderService->processDueReminders();
        
        $this->info("Processed: {$results['processed']} reminders");
        $this->info("Sent: {$results['sent']} notifications");
        
        if ($results['failed'] > 0) {
            $this->error("Failed: {$results['failed']} notifications");
        }
        
        return Command::SUCCESS;
    }
}
