<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\User;
use App\Notifications\EnrollmentStatusUpdated;
use App\Notifications\NewEnrollmentApplication;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class EnrollmentNotificationService
{
    /**
     * Send notification for new enrollment application
     */
    public function notifyNewApplication(Enrollment $enrollment)
    {
        try {
            // Get all teacher-admins for the school
            $admins = User::where('role', 'teacher-admin')
                         ->where('school_id', $enrollment->school_id)
                         ->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewEnrollmentApplication($enrollment));
                Log::info('New enrollment application notification sent', [
                    'enrollment_id' => $enrollment->id,
                    'admin_count' => $admins->count()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send new enrollment application notification', [
                'enrollment_id' => $enrollment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send notification for enrollment status update
     */
    public function notifyStatusUpdate(Enrollment $enrollment, $previousStatus = null)
    {
        try {
            // Create a temporary notifiable object for the applicant
            $notifiable = new class($enrollment) {
                use \Illuminate\Notifications\Notifiable;
                
                public $enrollment;
                
                public function __construct($enrollment) {
                    $this->enrollment = $enrollment;
                }
                
                public function routeNotificationForMail() {
                    return $this->enrollment->getNotificationEmail();
                }
                
                public function getKey() {
                    return $this->enrollment->id;
                }
            };

            $notifiable->notify(new EnrollmentStatusUpdated($enrollment, $previousStatus));
            
            Log::info('Enrollment status update notification sent', [
                'enrollment_id' => $enrollment->id,
                'status' => $enrollment->status,
                'previous_status' => $previousStatus,
                'email' => $enrollment->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send enrollment status update notification', [
                'enrollment_id' => $enrollment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send reminder notifications for pending applications
     */
    public function sendPendingReminders()
    {
        try {
            // Get enrollments that have been pending for more than 3 days
            $pendingEnrollments = Enrollment::where('status', 'pending')
                                           ->where('created_at', '<=', now()->subDays(3))
                                           ->with(['school'])
                                           ->get();

            foreach ($pendingEnrollments as $enrollment) {
                $admins = User::where('role', 'teacher-admin')
                             ->where('school_id', $enrollment->school_id)
                             ->get();

                if ($admins->isNotEmpty()) {
                    // Send reminder notification (reuse NewEnrollmentApplication with different subject)
                    Notification::send($admins, new NewEnrollmentApplication($enrollment));
                }
            }

            Log::info('Pending enrollment reminders sent', [
                'count' => $pendingEnrollments->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send pending enrollment reminders', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get enrollment statistics for notifications
     */
    public function getEnrollmentStats($schoolId = null)
    {
        $query = Enrollment::query();
        
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        return [
            'total' => $query->count(),
            'pending' => $query->where('status', 'pending')->count(),
            'approved' => $query->where('status', 'approved')->count(),
            'enrolled' => $query->where('status', 'enrolled')->count(),
            'rejected' => $query->where('status', 'rejected')->count(),
            'recent_applications' => $query->where('created_at', '>=', now()->subDays(7))->count(),
            'pending_over_3_days' => $query->where('status', 'pending')
                                          ->where('created_at', '<=', now()->subDays(3))
                                          ->count()
        ];
    }
}