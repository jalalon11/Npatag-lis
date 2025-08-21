<?php

namespace App\Console\Commands;

use App\Services\EnrollmentNotificationService;
use Illuminate\Console\Command;

class SendEnrollmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrollment:send-reminders {--days=3 : Number of days after which to send reminders}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for pending enrollment applications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending enrollment reminders...');
        
        $notificationService = app(EnrollmentNotificationService::class);
        $notificationService->sendPendingReminders();
        
        $this->info('Enrollment reminders sent successfully!');
        
        return Command::SUCCESS;
    }
}