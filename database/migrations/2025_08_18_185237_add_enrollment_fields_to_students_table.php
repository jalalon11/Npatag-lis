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
        Schema::table('students', function (Blueprint $table) {
            $table->string('guardian_email')->nullable()->after('guardian_contact');
            $table->unsignedBigInteger('enrollment_id')->nullable()->after('section_id');
            $table->string('school_year', 20)->nullable()->after('enrollment_id');
            
            $table->foreign('enrollment_id')->references('id')->on('enrollments')->onDelete('set null');
            $table->index('enrollment_id');
            $table->index('school_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropIndex(['enrollment_id']);
            $table->dropIndex(['school_year']);
            $table->dropColumn(['guardian_email', 'enrollment_id', 'school_year']);
        });
    }
};
