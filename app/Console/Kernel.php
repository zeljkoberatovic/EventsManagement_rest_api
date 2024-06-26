<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command('app:send-event-reminders')
            //->daily();
            // ->dailyAt('10:00'); ova komanda ce slati svakoga dana tacno u 10h
             ->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        //$this->load(__DIR__.'/Commands');
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
