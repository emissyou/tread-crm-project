<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Company;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        $leads = [
            [
                'title'        => 'CRM Software License - Acme Corp',
                'contact'      => 'sarah.johnson@acme.com',
                'company'      => 'Acme Corp',
                'source'       => 'webinar',
                'status'       => 'closed',
                'priority'     => 'high',
                'value'        => 12500.00,
                'notes'        => 'Interested in annual enterprise plan',
                'follow_up_date' => now()->addDays(3)->toDateString(),
            ],
            [
                'title'        => 'Demo Request - TechStart Inc',
                'contact'      => 'mike.chen@techstart.com',
                'company'      => 'TechStart Inc',
                'source'       => 'website',
                'status'       => 'negotiating',
                'priority'     => 'high',
                'value'        => 8500.00,
                'notes'        => 'Wants custom onboarding package',
                'follow_up_date' => now()->addDays(1)->toDateString(),
            ],
            [
                'title'        => 'Enterprise Contract Renewal',
                'contact'      => 'emily.davis@enterprise.com',
                'company'      => 'Enterprise Solutions',
                'source'       => 'referral',
                'status'       => 'closed',
                'priority'     => 'high',
                'value'        => 24000.00,
                'notes'        => 'Multi-year contract renewal',
                'follow_up_date' => now()->addDays(7)->toDateString(),
            ],
            [
                'title'        => 'Free Trial Conversion - Startup IO',
                'contact'      => 'david.wilson@startup.io',
                'company'      => 'Startup IO',
                'source'       => 'cold_call',
                'status'       => 'contacted',
                'priority'     => 'medium',
                'value'        => 3200.00,
                'notes'        => 'Currently on 14-day trial',
                'follow_up_date' => now()->addDays(2)->toDateString(),
            ],
            [
                'title'        => 'Upsell Premium Plan - Pro Consulting',
                'contact'      => 'lisa.martinez@proconsulting.com',
                'company'      => 'Pro Consulting',
                'source'       => 'referral',
                'status'       => 'negotiating',
                'priority'     => 'medium',
                'value'        => 6800.00,
                'notes'        => 'Upgrading from basic to premium',
                'follow_up_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'title'        => 'New Account - Global Trade Co',
                'contact'      => 'james.thompson@globaltrade.com',
                'company'      => 'Global Trade Co',
                'source'       => 'trade_show',
                'status'       => 'new',
                'priority'     => 'low',
                'value'        => 4500.00,
                'notes'        => 'Met at industry expo',
                'follow_up_date' => now()->addDays(10)->toDateString(),
            ],
            [
                'title'        => 'Healthcare CRM Package - HealthPlus',
                'contact'      => 'anna.lee@healthplus.com',
                'company'      => 'HealthPlus',
                'source'       => 'trade_show',
                'status'       => 'new',
                'priority'     => 'medium',
                'value'        => 9200.00,
                'notes'        => 'Needs HIPAA-compliant solution',
                'follow_up_date' => now()->addDays(4)->toDateString(),
            ],
            [
                'title'        => 'Education Bundle - EduTech Solutions',
                'contact'      => 'robert.brown@edutech.com',
                'company'      => 'EduTech Solutions',
                'source'       => 'email',
                'status'       => 'contacted',
                'priority'     => 'low',
                'value'        => 2800.00,
                'notes'        => 'Requested bulk seat pricing',
                'follow_up_date' => now()->addDays(6)->toDateString(),
            ],
            [
                'title'        => 'Lost Opportunity - Startup IO Follow-up',
                'contact'      => 'david.wilson@startup.io',
                'company'      => 'Startup IO',
                'source'       => 'website',
                'status'       => 'lost',
                'priority'     => 'low',
                'value'        => 1500.00,
                'notes'        => 'Chose competitor product',
                'follow_up_date' => null,
            ],
        ];

        foreach ($leads as $data) {
            $contact = Contact::where('email', $data['contact'])->first();
            $company = Company::where('name', $data['company'])->first();

            Lead::create([
                'title'          => $data['title'],
                'contact_id'     => optional($contact)->id,
                'company_id'     => optional($company)->id,
                'source'         => $data['source'],
                'status'         => $data['status'],
                'priority'       => $data['priority'],
                'value'          => $data['value'],
                'notes'          => $data['notes'],
                'follow_up_date' => $data['follow_up_date'],
                'assigned_to'    => optional($admin)->id,
                'created_at'     => now()->subDays(rand(1, 60)),
            ]);
        }
    }
}