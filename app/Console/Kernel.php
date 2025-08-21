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
        // Subscription check removed - payment functionality disabled

        // Cache school logos daily at 1 AM
        $schedule->command('app:cache-school-logos')->dailyAt('01:00');

        // Clean up old cached images monthly
        $schedule->command('app:clean-image-cache --days=30')->monthly();

        // Send enrollment reminders daily at 9 AM
        $schedule->command('enrollment:send-reminders')->dailyAt('09:00');
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
