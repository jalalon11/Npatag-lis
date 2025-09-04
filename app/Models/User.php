<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'address',
        'school_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is guardian
     */
    public function isGuardian(): bool
    {
        return $this->role === 'guardian';
    }

    /**
     * Get the school this user belongs to
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get sections where this user is the adviser (homeroom teacher)
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'adviser_id');
    }

    /**
     * Get subjects taught by this teacher
     */
    public function subjects()
    {
        // Use the section_subject pivot table to get subjects
        return $this->belongsToMany(Subject::class, 'section_subject', 'teacher_id', 'subject_id')
            ->withPivot('section_id')
            ->withTimestamps();
    }

    /**
     * Direct access to all subject IDs for this teacher via section_subject
     *
     * @return array
     */
    public function getSubjectIds()
    {
        return DB::table('section_subject')
            ->where('teacher_id', $this->id)
            ->pluck('subject_id')
            ->unique()
            ->toArray();
    }

    /**
     * Get attendances recorded by this teacher
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }



    /**
     * Get support tickets created by this user
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Get support messages sent by this user
     */
    public function supportMessages()
    {
        return $this->hasMany(SupportMessage::class);
    }

    /**
     * Get the admissions where this user is the guardian
     */
    public function guardianAdmissions()
    {
        return $this->hasMany(Enrollment::class, 'guardian_id');
    }

    /**
     * Get the students where this user is the guardian (through admissions)
     */
    public function guardianStudents()
    {
        return $this->hasManyThrough(Student::class, Enrollment::class, 'guardian_id', 'admission_id');
    }
}
