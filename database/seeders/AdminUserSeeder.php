<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'user_id' => 'ADMIN001',
                'password' => Hash::make('Admin@123456'),
                'phone_number' => '9876543210',
                'role' => 'admin',
                'status' => true,
                'is_active' => true,
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}