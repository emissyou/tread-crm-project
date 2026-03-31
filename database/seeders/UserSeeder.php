<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@treadcrm.com'
        ],[
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true
        ]);

        User::updateOrCreate([
            'email' => 'sarah@treadcrm.com'
        ],[
            'name' => 'Sarah Mitchell',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'is_active' => true
        ]);

        User::updateOrCreate([
            'email' => 'james@treadcrm.com'
        ],[
            'name' => 'James Carter',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'is_active' => true
        ]);
    }
}