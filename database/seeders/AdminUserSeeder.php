<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@dillicreamery.in'],
            [
                'name' => 'Dilli Creamery Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $admin->assignRole('super-admin');

        $customer = User::firstOrCreate(
            ['email' => 'customer@dillicreamery.in'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('customer123'),
                'email_verified_at' => now(),
                'phone' => '+91 98765 43210',
                'is_active' => true,
            ]
        );
        $customer->assignRole('customer');
    }
}
