<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'grade_level',
        'adviser_id',
        'school_id',
        'school_year',
        'student_limit',
        'building_id',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the school this section belongs to
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the building this section belongs to
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get the students in this section
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the adviser (teacher) of this section
     */
    public function adviser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adviser_id');
    }

    /**
     * Get the subjects taught in this section
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'section_subject')
            ->withPivot('teacher_id')
            ->withTimestamps();
    }

    /**
     * Get the teachers assigned to this section through subjects
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'section_subject', 'section_id', 'teacher_id')
            ->withPivot('subject_id')
            ->withTimestamps();
    }

    /**
     * Get the current student count in this section
     */
    public function getCurrentStudentCount(): int
    {
        return $this->students()->count();
    }

    /**
     * Check if the section has reached its student limit
     */
    public function isFull(): bool
    {
        if (!$this->student_limit) {
            return false; // No limit set
        }
        
        return $this->getCurrentStudentCount() >= $this->student_limit;
    }

    /**
     * Get the remaining capacity of the section
     */
    public function getRemainingCapacity(): int
    {
        if (!$this->student_limit) {
            return PHP_INT_MAX; // No limit set
        }
        
        return max(0, $this->student_limit - $this->getCurrentStudentCount());
    }

    /**
     * Check if the section can accommodate additional students
     */
    public function canAccommodate(int $additionalStudents = 1): bool
    {
        if (!$this->student_limit) {
            return true; // No limit set
        }
        
        return ($this->getCurrentStudentCount() + $additionalStudents) <= $this->student_limit;
    }

    /**
     * Get capacity information as an array
     */
    public function getCapacityInfo(): array
    {
        $currentCount = $this->getCurrentStudentCount();
        
        return [
            'current_count' => $currentCount,
            'student_limit' => $this->student_limit,
            'remaining_capacity' => $this->getRemainingCapacity(),
            'is_full' => $this->isFull(),
            'capacity_percentage' => $this->student_limit ? round(($currentCount / $this->student_limit) * 100, 1) : 0
        ];
    }
}
