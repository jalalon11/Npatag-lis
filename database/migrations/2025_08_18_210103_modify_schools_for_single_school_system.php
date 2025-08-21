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
        // Delete all schools except the first one (if any exist)
        $firstSchool = \App\Models\School::first();
        if ($firstSchool) {
            \App\Models\School::where('id', '!=', $firstSchool->id)->delete();
        }
        
        // Add a unique constraint to ensure only one school can exist
        Schema::table('schools', function (Blueprint $table) {
            $table->boolean('is_primary')->default(true)->after('is_active');
            $table->unique('is_primary', 'unique_primary_school');
        });
        
        // Set the remaining school as primary
        if ($firstSchool) {
            $firstSchool->update(['is_primary' => true]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropUnique('unique_primary_school');
            $table->dropColumn('is_primary');
        });
    }
};
