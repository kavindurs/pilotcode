<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create a super admin if it doesn't exist
        if (!Admin::where('email', 'admin@example.com')->exists()) {
            Admin::create([
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'phone_number' => '+1234567890',
                'role' => 'super_admin',
                'status' => 'active',
                'profile_picture' => null
            ]);

            echo "Super Admin created successfully!\n";
        }

        // Create sample staff members
        $sampleStaff = [
            [
                'name' => 'John Editor',
                'email' => 'editor@example.com',
                'password' => Hash::make('password'),
                'phone_number' => '+1234567891',
                'role' => 'editor',
                'status' => 'active'
            ],
            [
                'name' => 'Jane Accountant',
                'email' => 'accountant@example.com',
                'password' => Hash::make('password'),
                'phone_number' => '+1234567892',
                'role' => 'accountant',
                'status' => 'active'
            ],
            [
                'name' => 'Bob Worker',
                'email' => 'worker@example.com',
                'password' => Hash::make('password'),
                'phone_number' => '+1234567893',
                'role' => 'worker',
                'status' => 'inactive'
            ]
        ];

        foreach ($sampleStaff as $staff) {
            if (!Admin::where('email', $staff['email'])->exists()) {
                Admin::create($staff);
                echo "Created staff member: {$staff['name']}\n";
            }
        }

        echo "Staff seeding completed!\n";
    }
}
