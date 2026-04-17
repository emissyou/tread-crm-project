<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $users = User::all();

        if ($customers->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No customers or users found. Skipping Lead seeding.');
            return;
        }

        $leadsData = [
            [
                'name' => 'Sarah Johnson - Acme Corp',
                'email' => 'sarah.johnson@acme.com',
                'phone' => '+1-555-0101',
                'source' => 'webinar',
                'status' => 'qualified',
                'priority' => 'high',
                'expected_value' => 12500.00,
                'notes' => 'Interested in annual enterprise plan',
                'customer_id' => $customers->first()->id,
                'assigned_user_id' => $users->first()->id,
            ],
            [
                'name' => 'David Wilson - Startup IO',
                'email' => 'david.wilson@startup.io',
                'phone' => '+1-555-0404',
                'source' => 'website',
                'status' => 'new',
                'priority' => 'medium',
                'expected_value' => 3200.00,
                'notes' => 'Free trial user',
                'customer_id' => $customers->skip(3)->first()->id ?? $customers->first()->id,
                'assigned_user_id' => $users->first()->id,
            ],
            // Add more if you want
        ];

        foreach ($leadsData as $data) {
            Lead::create($data);
        }

        $this->command->info('Leads seeded successfully.');
    }
}