<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('company')->nullable();
            $table->string('avatar')->nullable();
            $table->text('content');
            $table->tinyInteger('rating')->unsigned()->default(5);
            $table->enum('type', ['product', 'consultancy', 'course', 'general'])->default('general');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('is_approved');
            $table->index('is_featured');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
