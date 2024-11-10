<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::where('email', 'admin@example.com')->delete();
        User::where('email', 'employee@example.com')->delete();
        User::where('email', 'supervisor@example.com')->delete();
        User::where('email', 'hrd@example.com')->delete();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Supervisor User',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'HRD User',
            'email' => 'hrd@example.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
