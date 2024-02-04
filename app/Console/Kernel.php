<?php

namespace App\Console;

use App\Console\Commands\DumpBrowsingHistoriesFromCacheToDbCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // we want to persist all the browsing histories of users to the database, but we want to do it at a low activity time
        // to not slow down the server, hence - midnight
        $schedule->command(DumpBrowsingHistoriesFromCacheToDbCommand::class)->dailyAt('03:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
