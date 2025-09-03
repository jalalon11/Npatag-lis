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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birth_date');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('student_id')->unique();
            $table->string('lrn')->unique();
            $table->text('address')->nullable();
            $table->string('guardian_name');
            $table->string('guardian_contact');
            $table->string('guardian_email')->nullable();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('preferred_grade_level');
            $table->foreignId('preferred_section_id')->nullable()->constrained('sections')->onDelete('set null');
            $table->enum('status', ['pending', 'approved', 'rejected', 'enrolled'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('assigned_section_id')->nullable()->constrained('sections')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->string('school_year');
            $table->timestamps();
            
            $table->index(['school_id', 'status']);
            $table->index(['preferred_grade_level', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
