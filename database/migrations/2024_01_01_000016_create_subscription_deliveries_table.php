<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
            $table->date('scheduled_date');
            $table->enum('status', ['scheduled', 'skipped', 'delivered', 'failed'])->default('scheduled');
            $table->foreignId('delivery_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index('scheduled_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_deliveries');
    }
};
