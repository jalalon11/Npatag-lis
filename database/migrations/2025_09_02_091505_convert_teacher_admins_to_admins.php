<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert all teacher admin users to regular admin users
        DB::table('users')
            ->where('is_teacher_admin', true)
            ->update(['role' => 'admin']);

        // Remove the is_teacher_admin column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_teacher_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the is_teacher_admin column
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_teacher_admin')->default(false)->after('school_id');
        });

        // Note: We cannot reliably restore which users were teacher admins
        // This would need to be done manually if rollback is required
    }
};
