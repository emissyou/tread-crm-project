<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            [
                'first_name' => 'Sarah',
                'last_name'  => 'Johnson',
                'email'      => 'sarah.johnson@acme.com',
                'phone'      => '+1-555-0101',
                'company'    => 'Acme Corp',
                'notes'      => 'Marketing lead from webinar',
                'status'     => 'customer',
            ],
            [
                'first_name' => 'Mike',
                'last_name'  => 'Chen',
                'email'      => 'mike.chen@techstart.com',
                'phone'      => '+1-555-0202',
                'company'    => 'TechStart Inc',
                'notes'      => 'Interested in CRM demo',
                'status'     => 'customer',
            ],
            [
                'first_name' => 'Emily',
                'last_name'  => 'Davis',
                'email'      => 'emily.davis@enterprise.com',
                'phone'      => '+1-555-0303',
                'company'    => 'Enterprise Solutions',
                'notes'      => 'Signed contract last week',
                'status'     => 'customer',
            ],
            [
                'first_name' => 'David',
                'last_name'  => 'Wilson',
                'email'      => 'david.wilson@startup.io',
                'phone'      => '+1-555-0404',
                'company'    => 'Startup IO',
                'notes'      => 'Free trial user',
                'status'     => 'lead',
            ],
            [
                'first_name' => 'Lisa',
                'last_name'  => 'Martinez',
                'email'      => 'lisa.martinez@proconsulting.com',
                'phone'      => '+1-555-0505',
                'company'    => 'Pro Consulting',
                'notes'      => 'Repeat customer - upsell opportunity',
                'status'     => 'customer',
            ],
            [
                'first_name' => 'James',
                'last_name'  => 'Thompson',
                'email'      => 'james.thompson@globaltrade.com',
                'phone'      => '+1-555-0606',
                'company'    => 'Global Trade Co',
                'notes'      => 'Referred by Lisa Martinez',
                'status'     => 'prospect',
            ],
            [
                'first_name' => 'Anna',
                'last_name'  => 'Lee',
                'email'      => 'anna.lee@healthplus.com',
                'phone'      => '+1-555-0707',
                'company'    => 'HealthPlus',
                'notes'      => 'Attended trade show booth',
                'status'     => 'lead',
            ],
            [
                'first_name' => 'Robert',
                'last_name'  => 'Brown',
                'email'      => 'robert.brown@edutech.com',
                'phone'      => '+1-555-0808',
                'company'    => 'EduTech Solutions',
                'notes'      => 'Requested pricing sheet',
                'status'     => 'prospect',
            ],
        ];

        foreach ($contacts as $data) {
            Contact::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}