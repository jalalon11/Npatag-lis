<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename enrollments table to admissions
        Schema::rename('enrollments', 'admissions');
        
        // Update foreign key column name in students table
        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('enrollment_id', 'admission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename foreign key column back in students table
        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('admission_id', 'enrollment_id');
        });
        
        // Rename admissions table back to enrollments
        Schema::rename('admissions', 'enrollments');
    }
};
