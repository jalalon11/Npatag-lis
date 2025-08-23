<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixStudentEnrollmentIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:fix-enrollment-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix students that are missing enrollment_id by linking them to their enrollment records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix student enrollment IDs...');
        
        // Find students without enrollment_id
        $studentsWithoutEnrollmentId = Student::whereNull('enrollment_id')->get();
        
        if ($studentsWithoutEnrollmentId->isEmpty()) {
            $this->info('No students found without enrollment_id. All students are properly linked.');
            return 0;
        }
        
        $this->info("Found {$studentsWithoutEnrollmentId->count()} students without enrollment_id.");
        
        $fixed = 0;
        $notFound = 0;
        
        foreach ($studentsWithoutEnrollmentId as $student) {
            // Try to find matching enrollment by student_id and other fields
            $enrollment = Enrollment::where('student_id', $student->student_id)
                ->where('first_name', $student->first_name)
                ->where('last_name', $student->last_name)
                ->where('status', 'enrolled')
                ->first();
            
            if ($enrollment) {
                $student->update([
                    'enrollment_id' => $enrollment->id,
                    'school_year' => $student->school_year ?? (now()->year . '-' . (now()->year + 1))
                ]);
                $fixed++;
                $this->line("✓ Fixed student: {$student->first_name} {$student->last_name} (ID: {$student->student_id})");
            } else {
                $notFound++;
                $this->warn("✗ No matching enrollment found for: {$student->first_name} {$student->last_name} (ID: {$student->student_id})");
            }
        }
        
        $this->info("\nSummary:");
        $this->info("- Fixed: {$fixed} students");
        $this->warn("- Not found: {$notFound} students");
        
        if ($notFound > 0) {
            $this->warn("\nStudents without matching enrollments may have been created manually.");
            $this->warn("These students will not appear in the teacher's advisory section until they have an enrollment_id.");
        }
        
        return 0;
    }
}
