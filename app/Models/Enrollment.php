<?php

namespace App\Models;

use App\Services\EnrollmentNotificationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admissions';
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'gender',
        'student_id',
        'lrn',
        'address',
        'guardian_name',
        'guardian_contact',
        'guardian_email',
        'email',
        'school_id',
        'preferred_grade_level',
        'preferred_section',
        'preferred_section_id',
        'status',
        'previous_status',
        'rejection_reason',
        'processed_by',
        'processed_at',
        'assigned_section_id',
        'notes',
        'school_year',
        'medical_conditions',
        'medications',
        'special_needs',
        'previous_school',
        'previous_grade_level',
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_contact_relationship',
        'application_date'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'processed_at' => 'datetime',
        'application_date' => 'datetime'
    ];

    protected static function booted()
    {
        // Send notification when new enrollment is created
        static::created(function ($enrollment) {
            $notificationService = app(EnrollmentNotificationService::class);
            $notificationService->notifyNewApplication($enrollment);
        });

        // Send notification when enrollment status is updated
        static::updating(function ($enrollment) {
            if ($enrollment->isDirty('status')) {
                $enrollment->previous_status = $enrollment->getOriginal('status');
            }
        });

        static::updated(function ($enrollment) {
            if (isset($enrollment->previous_status)) {
                $notificationService = app(EnrollmentNotificationService::class);
                $notificationService->notifyStatusUpdate($enrollment, $enrollment->previous_status);
            }
        });
    }

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function preferredSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'preferred_section_id');
    }

    public function assignedSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'assigned_section_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Helper methods
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isAdmitted(): bool
    {
        return $this->status === 'enrolled';
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeForGradeLevel($query, $gradeLevel)
    {
        return $query->where('preferred_grade_level', $gradeLevel);
    }

    /**
     * Get the email for notifications
     */
    public function getNotificationEmail()
    {
        return $this->email ?: $this->guardian_email;
    }

    /**
     * Get the grade level for display
     */
    public function getGradeLevelAttribute()
    {
        return $this->preferred_grade_level;
    }
}
