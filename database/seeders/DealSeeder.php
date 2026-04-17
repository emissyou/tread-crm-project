<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Database\Seeder;

class DealSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $users = User::all();

        if ($customers->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No customers or users found. Skipping Deal seeding.');
            return;
        }

        $admin = $users->first();

        $deals = [
            // Closed Won
            [
                'title'                => 'Acme Corp Annual License',
                'customer_id'          => $customers->where('email', 'sarah.johnson@acme.com')->first()?->id ?? $customers->first()->id,
                'stage'                => 'closed_won',
                'value'                => 12500.00,
                'notes'                => 'Annual enterprise plan signed',
                'assigned_user_id'     => $admin->id,
                'closed_date'          => now()->subMonths(5),
                'expected_close_date'  => now()->subMonths(5),
            ],
            [
                'title'                => 'Enterprise Solutions Renewal',
                'customer_id'          => $customers->where('email', 'emily.davis@enterprise.com')->first()?->id ?? $customers->first()->id,
                'stage'                => 'closed_won',
                'value'                => 24000.00,
                'notes'                => '3-year contract renewal',
                'assigned_user_id'     => $admin->id,
                'closed_date'          => now()->subMonths(4),
            ],
            [
                'title'                => 'TechStart CRM Bundle',
                'customer_id'          => $customers->where('email', 'mike.chen@techstart.com')->first()?->id ?? $customers->first()->id,
                'stage'                => 'closed_won',
                'value'                => 8500.00,
                'notes'                => 'Includes custom onboarding',
                'assigned_user_id'     => $admin->id,
                'closed_date'          => now()->subMonths(3),
            ],

            // In Progress
            [
                'title'                => 'Startup IO Conversion',
                'customer_id'          => $customers->where('email', 'david.wilson@startup.io')->first()?->id ?? $customers->first()->id,
                'stage'                => 'proposal',
                'value'                => 3200.00,
                'notes'                => 'Proposal sent, awaiting feedback',
                'assigned_user_id'     => $admin->id,
                'expected_close_date'  => now()->addDays(14),
            ],
            [
                'title'                => 'HealthPlus Annual Renewal',
                'customer_id'          => $customers->where('email', 'anna.lee@healthplus.com')->first()?->id ?? $customers->first()->id,
                'stage'                => 'negotiation',
                'value'                => 11000.00,
                'notes'                => 'Negotiating multi-year discount',
                'assigned_user_id'     => $admin->id,
                'expected_close_date'  => now()->addDays(10),
            ],
        ];

        foreach ($deals as $data) {
            Deal::create($data);
        }

        $this->command->info('Deals seeded successfully.');
    }
}