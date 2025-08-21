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
        // Drop payment-related tables
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_method_settings');
        
        // Remove subscription fields from schools table
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_status',
                'subscription_ends_at',
                'billing_cycle',
                'monthly_price'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate payment_method_settings table
        Schema::create('payment_method_settings', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->boolean('enabled')->default(false);
            $table->text('message')->nullable();
            $table->timestamps();
        });
        
        // Recreate payments table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('payment_method');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'semi-annual', 'annual']);
            $table->date('subscription_start_date')->nullable();
            $table->date('subscription_end_date')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
        
        // Add subscription fields back to schools table
        Schema::table('schools', function (Blueprint $table) {
            $table->enum('subscription_status', ['active', 'expired', 'pending'])->default('pending');
            $table->timestamp('subscription_ends_at')->nullable();
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'semi-annual', 'annual'])->default('monthly');
            $table->decimal('monthly_price', 8, 2)->default(0.00);
        });
    }
};
