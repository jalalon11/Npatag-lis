<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Section;
use App\Models\School;

class Grade2SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first school (assuming school_id = 1)
        $schoolId = 1;
        
        // Check if Grade 2 section already exists
        $existingSection = Section::where('school_id', $schoolId)
            ->where('grade_level', 'Grade 2')
            ->first();
            
        if ($existingSection) {
            $this->command->info('Grade 2 section already exists: ' . $existingSection->name);
            return;
        }
        
        // Create a Grade 2 section
        $section = Section::create([
            'name' => 'Grade 2 - Section A',
            'grade_level' => 'Grade 2',
            'school_id' => $schoolId,
            'school_year' => '2024-2025',
            'student_limit' => 30,
            'is_active' => true,
            'adviser_id' => null // Can be assigned later
        ]);
        
        $this->command->info('Created Grade 2 section: ' . $section->name . ' (ID: ' . $section->id . ')');
    }
}