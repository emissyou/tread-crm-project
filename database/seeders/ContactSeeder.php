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
                'status'     => 'customer',
                'notes'      => 'Marketing lead from webinar',
            ],
            [
                'first_name' => 'Mike',
                'last_name'  => 'Chen',
                'email'      => 'mike.chen@techstart.com',
                'phone'      => '+1-555-0202',
                'company'    => 'TechStart Inc',
                'status'     => 'customer',
                'notes'      => 'Interested in CRM demo',
            ],
            [
                'first_name' => 'Emily',
                'last_name'  => 'Davis',
                'email'      => 'emily.davis@enterprise.com',
                'phone'      => '+1-555-0303',
                'company'    => 'Enterprise Solutions',
                'status'     => 'customer',
                'notes'      => 'Signed contract last week',
            ],
            [
                'first_name' => 'David',
                'last_name'  => 'Wilson',
                'email'      => 'david.wilson@startup.io',
                'phone'      => '+1-555-0404',
                'company'    => 'Startup IO',
                'status'     => 'lead',
                'notes'      => 'Free trial user',
            ],
            [
                'first_name' => 'Lisa',
                'last_name'  => 'Martinez',
                'email'      => 'lisa.martinez@proconsulting.com',
                'phone'      => '+1-555-0505',
                'company'    => 'Pro Consulting',
                'status'     => 'customer',
                'notes'      => 'Repeat customer - upsell opportunity',
            ],
            [
                'first_name' => 'James',
                'last_name'  => 'Thompson',
                'email'      => 'james.thompson@globaltrade.com',
                'phone'      => '+1-555-0606',
                'company'    => 'Global Trade Co',
                'status'     => 'prospect',
                'notes'      => 'Referred by Lisa Martinez',
            ],
            [
                'first_name' => 'Anna',
                'last_name'  => 'Lee',
                'email'      => 'anna.lee@healthplus.com',
                'phone'      => '+1-555-0707',
                'company'    => 'HealthPlus',
                'status'     => 'lead',
                'notes'      => 'Attended trade show booth',
            ],
            [
                'first_name' => 'Robert',
                'last_name'  => 'Brown',
                'email'      => 'robert.brown@edutech.com',
                'phone'      => '+1-555-0808',
                'company'    => 'EduTech Solutions',
                'status'     => 'prospect',
                'notes'      => 'Requested pricing sheet',
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