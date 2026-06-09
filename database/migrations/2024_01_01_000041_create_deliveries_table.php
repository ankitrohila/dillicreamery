<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('subscription_delivery_id')->nullable()->constrained('subscription_deliveries')->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('delivery_agents')->nullOnDelete();
            $table->enum('status', ['pending', 'assigned', 'picked', 'in_transit', 'delivered', 'failed'])->default('pending');
            $table->timestamp('pickup_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('otp', 10)->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
