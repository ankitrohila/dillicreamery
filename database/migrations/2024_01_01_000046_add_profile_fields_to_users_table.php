<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->date('date_of_birth')->nullable()->after('avatar');
            $table->string('gender', 20)->nullable()->after('date_of_birth');
            $table->text('bio')->nullable()->after('gender');
            $table->boolean('is_active')->default(true)->after('bio');
            $table->text('two_factor_secret')->nullable()->after('is_active');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('last_login_at')->nullable()->after('two_factor_recovery_codes');

            $table->index('phone');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['phone']);
            $table->dropIndex(['is_active']);
            $table->dropColumn([
                'phone',
                'avatar',
                'date_of_birth',
                'gender',
                'bio',
                'is_active',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'last_login_at',
            ]);
        });
    }
};
