<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['name' => 'Acme Corp',            'industry' => 'Manufacturing',  'website' => 'https://acme.com',          'phone' => '+1-555-1000', 'address' => '123 Main St, New York, NY'],
            ['name' => 'TechStart Inc',        'industry' => 'Technology',     'website' => 'https://techstart.com',     'phone' => '+1-555-2000', 'address' => '456 Silicon Ave, San Francisco, CA'],
            ['name' => 'Enterprise Solutions', 'industry' => 'Consulting',     'website' => 'https://enterprise.com',    'phone' => '+1-555-3000', 'address' => '789 Business Blvd, Chicago, IL'],
            ['name' => 'Startup IO',           'industry' => 'SaaS',           'website' => 'https://startup.io',        'phone' => '+1-555-4000', 'address' => '321 Innovation Dr, Austin, TX'],
            ['name' => 'Pro Consulting',       'industry' => 'Consulting',     'website' => 'https://proconsulting.com', 'phone' => '+1-555-5000', 'address' => '654 Strategy Ln, Boston, MA'],
            ['name' => 'Global Trade Co',      'industry' => 'Retail',         'website' => 'https://globaltrade.com',   'phone' => '+1-555-6000', 'address' => '987 Commerce St, Miami, FL'],
            ['name' => 'HealthPlus',           'industry' => 'Healthcare',     'website' => 'https://healthplus.com',    'phone' => '+1-555-7000', 'address' => '111 Wellness Way, Seattle, WA'],
            ['name' => 'EduTech Solutions',    'industry' => 'Education',      'website' => 'https://edutech.com',       'phone' => '+1-555-8000', 'address' => '222 Learning Rd, Denver, CO'],
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(['name' => $company['name']], $company);
        }
    }
}