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
        Schema::table('schools', function (Blueprint $table) {
            // Add division fields directly to schools table
            $table->string('division_name')->nullable()->after('name');
            $table->string('division_code')->nullable()->after('division_name');
            $table->text('division_address')->nullable()->after('division_code');
            $table->string('region')->nullable()->after('division_address');
            
            // Remove the foreign key constraint and column
            $table->dropForeign(['school_division_id']);
            $table->dropColumn('school_division_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Remove division fields
            $table->dropColumn(['division_name', 'division_code', 'division_address', 'region']);
            
            // Add back the school_division_id foreign key
            $table->foreignId('school_division_id')->nullable()->constrained('school_divisions')->onDelete('set null');
        });
    }
};
