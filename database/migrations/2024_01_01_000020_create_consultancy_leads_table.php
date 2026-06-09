<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultancy_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->string('business_name')->nullable();
            $table->text('message')->nullable();
            $table->foreignId('service_id')->nullable()->constrained('consultancy_services')->nullOnDelete();
            $table->enum('status', ['new', 'contacted', 'qualified', 'proposal_sent', 'won', 'lost'])->default('new');
            $table->string('source')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultancy_leads');
    }
};
