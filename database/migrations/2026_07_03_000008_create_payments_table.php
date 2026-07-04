<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('customer_membership_id')->nullable()->constrained('customer_memberships')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('registered_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('payment_date');
            $table->string('receipt_number', 80)->nullable()->unique();
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'payment_date']);
            $table->index(['customer_membership_id', 'payment_date']);
            $table->index(['payment_date', 'payment_method_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
