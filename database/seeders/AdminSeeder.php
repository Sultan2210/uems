<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@uems.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'admin_approval_status' => 'approved',
            'is_active' => true,
        ]);
    }
}
