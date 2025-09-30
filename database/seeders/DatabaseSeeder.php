<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'address' => 'School Administration Building',
        ]);

        // Create principal user
        User::create([
            'name' => 'Principal User',
            'email' => 'principal@school.com',
            'password' => Hash::make('password'),
            'role' => 'principal',
            'phone' => '+1234567891',
            'address' => 'Principal Office',
        ]);

        // Create sample parent user
        User::create([
            'name' => 'John Parent',
            'email' => 'parent@example.com',
            'password' => Hash::make('password'),
            'role' => 'parent',
            'phone' => '+1234567892',
            'address' => '123 Main Street, City',
        ]);

        $this->command->info('Default users created successfully!');
        $this->command->info('Admin Login: admin@school.com / password');
        $this->command->info('Principal Login: principal@school.com / password');
        $this->command->info('Parent Login: parent@example.com / password');
    }
};
