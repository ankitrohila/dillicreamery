<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_url');
            $table->enum('type', ['ebook', 'guide', 'recipe', 'certificate'])->default('guide');
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();

            $table->index('type');
            $table->index('is_free');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
