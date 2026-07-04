<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('customer_membership_id')->nullable()->constrained('customer_memberships')->cascadeOnUpdate()->nullOnDelete();
            $table->enum('type', ['expires_today', 'expires_in_3_days', 'expired']);
            $table->string('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('generated_at');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'is_read']);
            $table->index(['generated_at', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
