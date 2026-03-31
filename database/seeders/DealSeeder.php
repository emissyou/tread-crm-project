<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Database\Seeder;

class DealSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        $deals = [
            // ── Closed Won (contribute to revenue) ───────────────────────────
            [
                'title'      => 'Acme Corp Annual License',
                'contact'    => 'sarah.johnson@acme.com',
                'stage'      => 'closed_won',
                'value'      => 12500.00,
                'notes'      => 'Annual enterprise plan signed',
                'created_at' => now()->subMonths(5)->subDays(3),
                'updated_at' => now()->subMonths(5),
            ],
            [
                'title'      => 'Enterprise Solutions Renewal',
                'contact'    => 'emily.davis@enterprise.com',
                'stage'      => 'closed_won',
                'value'      => 24000.00,
                'notes'      => '3-year contract renewal',
                'created_at' => now()->subMonths(4)->subDays(5),
                'updated_at' => now()->subMonths(4),
            ],
            [
                'title'      => 'Pro Consulting Premium Upgrade',
                'contact'    => 'lisa.martinez@proconsulting.com',
                'stage'      => 'closed_won',
                'value'      => 6800.00,
                'notes'      => 'Upgraded from basic to premium tier',
                'created_at' => now()->subMonths(4)->subDays(2),
                'updated_at' => now()->subMonths(4),
            ],
            [
                'title'      => 'TechStart CRM Bundle',
                'contact'    => 'mike.chen@techstart.com',
                'stage'      => 'closed_won',
                'value'      => 8500.00,
                'notes'      => 'Includes custom onboarding',
                'created_at' => now()->subMonths(3)->subDays(8),
                'updated_at' => now()->subMonths(3),
            ],
            [
                'title'      => 'Global Trade Co Starter Pack',
                'contact'    => 'james.thompson@globaltrade.com',
                'stage'      => 'closed_won',
                'value'      => 4500.00,
                'notes'      => 'Starter pack with 20 seats',
                'created_at' => now()->subMonths(3)->subDays(1),
                'updated_at' => now()->subMonths(3),
            ],
            [
                'title'      => 'HealthPlus HIPAA Package',
                'contact'    => 'anna.lee@healthplus.com',
                'stage'      => 'closed_won',
                'value'      => 9200.00,
                'notes'      => 'Healthcare-compliant CRM setup',
                'created_at' => now()->subMonths(2)->subDays(4),
                'updated_at' => now()->subMonths(2),
            ],
            [
                'title'      => 'Acme Corp Add-on Modules',
                'contact'    => 'sarah.johnson@acme.com',
                'stage'      => 'closed_won',
                'value'      => 3800.00,
                'notes'      => 'Additional reporting modules',
                'created_at' => now()->subMonths(2)->subDays(1),
                'updated_at' => now()->subMonths(2),
            ],
            [
                'title'      => 'EduTech Bulk License',
                'contact'    => 'robert.brown@edutech.com',
                'stage'      => 'closed_won',
                'value'      => 2800.00,
                'notes'      => '50-seat bulk discount applied',
                'created_at' => now()->subMonth()->subDays(10),
                'updated_at' => now()->subMonth(),
            ],
            [
                'title'      => 'Pro Consulting Team Expansion',
                'contact'    => 'lisa.martinez@proconsulting.com',
                'stage'      => 'closed_won',
                'value'      => 5200.00,
                'notes'      => 'Added 15 more seats',
                'created_at' => now()->subMonth()->subDays(5),
                'updated_at' => now()->subMonth(),
            ],
            [
                'title'      => 'TechStart Premium Support',
                'contact'    => 'mike.chen@techstart.com',
                'stage'      => 'closed_won',
                'value'      => 4200.00,
                'notes'      => 'Priority support subscription',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],

            // ── In Progress ───────────────────────────────────────────────────
            [
                'title'      => 'Startup IO Conversion',
                'contact'    => 'david.wilson@startup.io',
                'stage'      => 'proposal',       // was 'proposal' ✓ valid
                'value'      => 3200.00,
                'notes'      => 'Proposal sent, awaiting feedback',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'title'      => 'HealthPlus Annual Renewal',
                'contact'    => 'anna.lee@healthplus.com',
                'stage'      => 'negotiation',    // was 'negotiation' ✓ valid
                'value'      => 11000.00,
                'notes'      => 'Negotiating multi-year discount',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'title'      => 'Global Trade Enterprise',
                'contact'    => 'james.thompson@globaltrade.com',
                'stage'      => 'qualification',  // was 'qualified' ✗ — fixed
                'value'      => 7500.00,
                'notes'      => 'Qualified — scheduling demo',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],

            // ── Closed Lost ───────────────────────────────────────────────────
            [
                'title'      => 'Startup IO Initial Pitch',
                'contact'    => 'david.wilson@startup.io',
                'stage'      => 'closed_lost',
                'value'      => 1500.00,
                'notes'      => 'Chose a competitor product',
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subMonths(2),
            ],
        ];

        foreach ($deals as $data) {
            $contact = Contact::where('email', $data['contact'])->first();

            Deal::create([
                'title'       => $data['title'],
                'contact_id'  => optional($contact)->id,
                'stage'       => $data['stage'],
                'value'       => $data['value'],
                'notes'       => $data['notes'],
                'assigned_to' => optional($admin)->id,
                'created_at'  => $data['created_at'],
                'updated_at'  => $data['updated_at'],
            ]);
        }
    }
}