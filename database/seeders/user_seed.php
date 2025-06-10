<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class user_seed extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@sipubi.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => true,
        ]);
    }
}