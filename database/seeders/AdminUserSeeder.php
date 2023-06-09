<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'mobile' => '9876543210',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
        ])->assignRole('admin');
    }
}
