<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEnrollmentApplication extends Notification implements ShouldQueue
{
    use Queueable;

    protected $enrollment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Enrollment Application - ' . $this->enrollment->school->name)
                    ->greeting('Hello Administrator,')
                    ->line('A new enrollment application has been submitted and requires your review.')
                    ->line('Student Name: ' . $this->enrollment->first_name . ' ' . $this->enrollment->last_name)
                    ->line('Application ID: ' . str_pad($this->enrollment->id, 6, '0', STR_PAD_LEFT))
                    ->line('School: ' . $this->enrollment->school->name)
                    ->line('Grade Level: ' . $this->enrollment->grade_level)
                    ->line('Preferred Section: ' . ($this->enrollment->preferredSection?->name ?? 'Not specified'))
                    ->line('Application Date: ' . $this->enrollment->created_at->format('M d, Y h:i A'))
                    ->action('Review Application', route('admin.admissions.show', $this->enrollment->id))
                    ->line('Please review and process this application at your earliest convenience.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'admission_id' => $this->enrollment->id,
            'student_name' => $this->enrollment->first_name . ' ' . $this->enrollment->last_name,
            'school_name' => $this->enrollment->school->name,
            'grade_level' => $this->enrollment->grade_level,
            'preferred_section' => $this->enrollment->preferredSection?->name,
            'application_date' => $this->enrollment->created_at->toISOString(),
            'type' => 'new_enrollment_application'
        ];
    }
}