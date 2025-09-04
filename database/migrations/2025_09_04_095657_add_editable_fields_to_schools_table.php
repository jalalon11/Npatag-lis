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
            // Add only missing columns (name, address, grade_levels already exist)
            if (!Schema::hasColumn('schools', 'code')) {
                $table->string('code', 100)->nullable()->after('name');
            }
            if (!Schema::hasColumn('schools', 'region')) {
                $table->string('region')->nullable()->after('address');
            }
            if (!Schema::hasColumn('schools', 'division_name')) {
                $table->string('division_name')->nullable()->after('grade_levels');
            }
            if (!Schema::hasColumn('schools', 'division_code')) {
                $table->string('division_code', 100)->nullable()->after('division_name');
            }
            if (!Schema::hasColumn('schools', 'division_address')) {
                $table->text('division_address')->nullable()->after('division_code');
            }
            if (!Schema::hasColumn('schools', 'principal')) {
                $table->string('principal')->nullable()->after('division_address');
            }
            if (!Schema::hasColumn('schools', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('principal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'code',
                'region',
                'division_name',
                'division_code',
                'division_address',
                'principal',
                'logo_path'
            ]);
        });
    }
};
