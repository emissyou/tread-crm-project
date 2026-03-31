<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        $tasks = [
            [
                'title'       => 'Follow up with Mike Chen',
                'contact'     => 'mike.chen@techstart.com',
                'due_date'    => now()->toDateString(),
                'priority'    => 'high',
                'status'      => 'pending',
                'description' => 'Discuss custom onboarding package pricing',
            ],
            [
                'title'       => 'Demo call - David Wilson',
                'contact'     => 'david.wilson@startup.io',
                'due_date'    => now()->addDay()->toDateString(),
                'priority'    => 'high',
                'status'      => 'pending',
                'description' => 'Prepare CRM demo for Startup IO',
            ],
            [
                'title'       => 'Contract review - Emily Davis',
                'contact'     => 'emily.davis@enterprise.com',
                'due_date'    => now()->addDays(4)->toDateString(),
                'priority'    => 'medium',
                'status'      => 'pending',
                'description' => 'Review renewal terms before sending',
            ],
            [
                'title'       => 'Send proposal to Anna Lee',
                'contact'     => 'anna.lee@healthplus.com',
                'due_date'    => now()->addDays(2)->toDateString(),
                'priority'    => 'medium',
                'status'      => 'pending',
                'description' => 'Include HIPAA compliance documentation',
            ],
            [
                'title'       => 'Check in with James Thompson',
                'contact'     => 'james.thompson@globaltrade.com',
                'due_date'    => now()->addDays(6)->toDateString(),
                'priority'    => 'low',
                'status'      => 'pending',
                'description' => 'See if they are ready to schedule a demo',
            ],
            [
                'title'       => 'Upsell call - Lisa Martinez',
                'contact'     => 'lisa.martinez@proconsulting.com',
                'due_date'    => now()->addDays(3)->toDateString(),
                'priority'    => 'medium',
                'status'      => 'in_progress',
                'description' => 'Discuss team expansion options',
            ],
            [
                'title'       => 'Send invoice - Sarah Johnson',
                'contact'     => 'sarah.johnson@acme.com',
                'due_date'    => now()->addDays(1)->toDateString(),
                'priority'    => 'high',
                'status'      => 'pending',
                'description' => 'Send Q2 invoice for add-on modules',
            ],
            [
                'title'       => 'Onboarding session - Robert Brown',
                'contact'     => 'robert.brown@edutech.com',
                'due_date'    => now()->addDays(5)->toDateString(),
                'priority'    => 'medium',
                'status'      => 'pending',
                'description' => 'Walk through bulk license setup',
            ],
            [
                'title'       => 'Initial call - Sarah Johnson',
                'contact'     => 'sarah.johnson@acme.com',
                'due_date'    => now()->subDays(5)->toDateString(),
                'priority'    => 'high',
                'status'      => 'completed',
                'description' => 'Introductory call completed successfully',
            ],
        ];

        foreach ($tasks as $data) {
            $contact = Contact::where('email', $data['contact'])->first();

            Task::create([
                'title'       => $data['title'],
                'contact_id'  => optional($contact)->id,
                'due_date'    => $data['due_date'],
                'priority'    => $data['priority'],
                'status'      => $data['status'],
                'description' => $data['description'],
                'assigned_to' => optional($admin)->id,
            ]);
        }
    }
}