<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnrollmentStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $enrollment;
    protected $previousStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Enrollment $enrollment, $previousStatus = null)
    {
        $this->enrollment = $enrollment;
        $this->previousStatus = $previousStatus;
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
        $statusMessages = [
            'pending' => 'Your enrollment application is being reviewed.',
            'approved' => 'Congratulations! Your enrollment application has been approved.',
            'rejected' => 'We regret to inform you that your enrollment application has been rejected.',
            'enrolled' => 'You have been successfully enrolled and assigned to a section.'
        ];

        $message = new MailMessage();
        $message->subject('Enrollment Application Status Update - ' . $this->enrollment->school->name)
                ->greeting('Hello ' . $this->enrollment->first_name . ' ' . $this->enrollment->last_name . ',')
                ->line($statusMessages[$this->enrollment->status] ?? 'Your enrollment status has been updated.')
                ->line('Application ID: ' . str_pad($this->enrollment->id, 6, '0', STR_PAD_LEFT))
                ->line('School: ' . $this->enrollment->school->name)
                ->line('Grade Level: ' . $this->enrollment->grade_level);

        if ($this->enrollment->status === 'approved' && $this->enrollment->assignedSection) {
            $message->line('Assigned Section: ' . $this->enrollment->assignedSection->name);
        }

        if ($this->enrollment->status === 'rejected' && $this->enrollment->notes) {
            $message->line('Reason: ' . $this->enrollment->notes);
        }

        if ($this->enrollment->status === 'enrolled' && $this->enrollment->assignedSection) {
            $message->line('Section: ' . $this->enrollment->assignedSection->name)
                    ->line('You can now access your student portal and begin your academic journey.');
        }

        $message->action('Check Application Status', route('enrollment.status.form'))
                ->line('If you have any questions, please contact the school directly.');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'enrollment_id' => $this->enrollment->id,
            'student_name' => $this->enrollment->first_name . ' ' . $this->enrollment->last_name,
            'school_name' => $this->enrollment->school->name,
            'status' => $this->enrollment->status,
            'previous_status' => $this->previousStatus,
            'grade_level' => $this->enrollment->grade_level,
            'assigned_section' => $this->enrollment->assignedSection?->name,
            'notes' => $this->enrollment->notes,
            'updated_at' => $this->enrollment->updated_at->toISOString()
        ];
    }
}