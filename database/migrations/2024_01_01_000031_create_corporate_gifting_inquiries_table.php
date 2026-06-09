<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corporate_gifting_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->string('company_name')->nullable();
            $table->string('occasion')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->decimal('budget', 10, 2)->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['new', 'in_progress', 'quoted', 'confirmed', 'completed'])->default('new');
            $table->timestamps();

            $table->index('status');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corporate_gifting_inquiries');
    }
};
