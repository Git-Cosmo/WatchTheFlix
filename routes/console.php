<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule EPG Updates - Run daily at 3 AM
Schedule::command('epg:update')->daily()->at('03:00');

// Schedule EPG Reminders Processing - Run every 5 minutes
Schedule::command('epg:process-reminders')->everyFiveMinutes();

// Cleanup old EPG reminders - Run weekly
Schedule::command('epg:cleanup-reminders --days=30')->weekly()->sundays()->at('04:00');

// Warm up stream cache - Run every hour
Schedule::command('stream:warmup-cache')->hourly();
