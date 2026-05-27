<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = [
            [
                'name'      => 'Admin User',
                'email'     => 'admin@clinic.com',
                'role'      => 'admin',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'Dr. Sample Doctor',
                'email'     => 'doctor@clinic.com',
                'role'      => 'doctor',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'Reception Staff',
                'email'     => 'receptionist@clinic.com',
                'role'      => 'receptionist',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(['email' => $data['email']], $data);
        }
    }
}
