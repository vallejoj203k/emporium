<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('membership_plan_id')->constrained('membership_plans')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('registered_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('paid_amount', 12, 2);
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index(['end_date', 'status']);
            $table->index(['membership_plan_id', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_memberships');
    }
};
