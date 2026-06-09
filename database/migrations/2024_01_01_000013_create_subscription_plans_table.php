<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('frequency', ['daily', 'alternate_day', 'weekly', 'monthly', 'custom'])->default('daily');
            $table->decimal('price_per_delivery', 10, 2)->default(0);
            $table->unsignedInteger('min_deliveries')->default(1);
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable();
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
