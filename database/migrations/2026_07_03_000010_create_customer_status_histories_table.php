<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('changed_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->enum('previous_status', ['active', 'suspended', 'expired'])->nullable();
            $table->enum('new_status', ['active', 'suspended', 'expired']);
            $table->text('reason')->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();

            $table->index(['customer_id', 'changed_at']);
            $table->index(['new_status', 'changed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_status_histories');
    }
};
