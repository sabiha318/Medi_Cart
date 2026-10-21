<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@medicart.test'],
            [
                'name' => 'MediCart Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '01700000000',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@medicart.test'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '01800000000',
            ]
        );
    }
}
