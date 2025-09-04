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
        Schema::table('admissions', function (Blueprint $table) {
            // Additional Information fields
            $table->string('previous_school')->nullable()->after('guardian_email');
            $table->string('previous_grade_level')->nullable()->after('previous_school');
            
            // Emergency Contact fields
            $table->string('emergency_contact_name')->nullable()->after('previous_grade_level');
            $table->string('emergency_contact_number')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_number');
            
            // Medical Information fields
            $table->text('medical_conditions')->nullable()->after('emergency_contact_relationship');
            $table->text('medications')->nullable()->after('medical_conditions');
            $table->text('special_needs')->nullable()->after('medications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn([
                'previous_school',
                'previous_grade_level',
                'emergency_contact_name',
                'emergency_contact_number',
                'emergency_contact_relationship',
                'medical_conditions',
                'medications',
                'special_needs'
            ]);
        });
    }
};