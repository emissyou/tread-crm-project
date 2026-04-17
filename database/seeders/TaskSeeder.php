<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $users = User::all();

        if ($customers->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No customers or users found. Skipping Task seeding.');
            return;
        }

        $admin = $users->first();

        $tasks = [
            [
                'title'          => 'Follow up with Mike Chen',
                'customer_id'    => $customers->where('email', 'mike.chen@techstart.com')->first()?->id ?? $customers->first()->id,
                'due_date'       => now()->toDateString(),
                'priority'       => 'high',
                'status'         => 'pending',
                'description'    => 'Discuss custom onboarding package pricing',
                'assigned_user_id' => $admin->id,
                'created_by'     => $admin->id,
            ],
            [
                'title'          => 'Demo call - David Wilson',
                'customer_id'    => $customers->where('email', 'david.wilson@startup.io')->first()?->id ?? $customers->first()->id,
                'due_date'       => now()->addDay()->toDateString(),
                'priority'       => 'high',
                'status'         => 'pending',
                'description'    => 'Prepare CRM demo for Startup IO',
                'assigned_user_id' => $admin->id,
                'created_by'     => $admin->id,
            ],
            [
                'title'          => 'Contract review - Emily Davis',
                'customer_id'    => $customers->where('email', 'emily.davis@enterprise.com')->first()?->id ?? $customers->first()->id,
                'due_date'       => now()->addDays(4)->toDateString(),
                'priority'       => 'medium',
                'status'         => 'pending',
                'description'    => 'Review renewal terms before sending',
                'assigned_user_id' => $admin->id,
                'created_by'     => $admin->id,
            ],
            [
                'title'          => 'Send proposal to Anna Lee',
                'customer_id'    => $customers->where('email', 'anna.lee@healthplus.com')->first()?->id ?? $customers->first()->id,
                'due_date'       => now()->addDays(2)->toDateString(),
                'priority'       => 'medium',
                'status'         => 'pending',
                'description'    => 'Include HIPAA compliance documentation',
                'assigned_user_id' => $admin->id,
                'created_by'     => $admin->id,
            ],
            [
                'title'          => 'Check in with James Thompson',
                'customer_id'    => $customers->where('email', 'james.thompson@globaltrade.com')->first()?->id ?? $customers->first()->id,
                'due_date'       => now()->addDays(6)->toDateString(),
                'priority'       => 'low',
                'status'         => 'pending',
                'description'    => 'See if they are ready to schedule a demo',
                'assigned_user_id' => $admin->id,
                'created_by'     => $admin->id,
            ],
            [
                'title'          => 'Upsell call - Lisa Martinez',
                'customer_id'    => $customers->where('email', 'lisa.martinez@proconsulting.com')->first()?->id ?? $customers->first()->id,
                'due_date'       => now()->addDays(3)->toDateString(),
                'priority'       => 'medium',
                'status'         => 'in_progress',
                'description'    => 'Discuss team expansion options',
                'assigned_user_id' => $admin->id,
                'created_by'     => $admin->id,
            ],
        ];

        foreach ($tasks as $data) {
            Task::create($data);
        }

        $this->command->info('Tasks seeded successfully.');
    }
}