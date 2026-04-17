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
        ], [
            'name'     => 'Admin',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            // removed is_active
        ]);

        User::updateOrCreate([
            'email' => 'manager@treadcrm.com'
        ], [
            'name'     => 'Manager',
            'password' => Hash::make('password'),
            'role'     => 'manager',
            // removed is_active
        ]);

        User::updateOrCreate([
            'email' => 'sarah@treadcrm.com'
        ], [
            'name'     => 'Sarah Mitchell',
            'password' => Hash::make('password'),
            'role'     => 'sales_staff',
            // removed is_active
        ]);

        User::updateOrCreate([
            'email' => 'james@treadcrm.com'
        ], [
            'name'     => 'James Carter',
            'password' => Hash::make('password'),
            'role'     => 'sales_staff',
            // removed is_active
        ]);
    }
}