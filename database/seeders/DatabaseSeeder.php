<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------------------------------------
        // 1. USERS (5 users — referenced by other tables)
        // -------------------------------------------------------
        $userIds = [];
        $users = [
            ['name' => 'John Mark Bolanon', 'email' => 'johnmarkbolanon@gmail.com'],
            ['name' => 'Maria Santos',       'email' => 'maria.santos@treadcrm.com'],
            ['name' => 'Carlo Reyes',        'email' => 'carlo.reyes@treadcrm.com'],
            ['name' => 'Ana Lim',            'email' => 'ana.lim@treadcrm.com'],
            ['name' => 'Paolo Cruz',         'email' => 'paolo.cruz@treadcrm.com'],
        ];
        foreach ($users as $user) {
            // If user already exists, just grab their ID — don't insert again
            $existing = DB::table('users')->where('email', $user['email'])->first();
            if ($existing) {
                $userIds[] = $existing->id;
            } else {
                $id = DB::table('users')->insertGetId([
                    'name'              => $user['name'],
                    'email'             => $user['email'],
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
                $userIds[] = $id;
            }
        }

        // -------------------------------------------------------
        // 2. COMPANIES (50 records)
        // -------------------------------------------------------
        $industries = ['Technology', 'Finance', 'Healthcare', 'Retail', 'Manufacturing', 'Education', 'Real Estate', 'Marketing', 'Logistics', 'Food & Beverage'];
        $statuses   = ['active', 'inactive', 'prospect'];
        $companyIds = [];

        $companyNames = [
            'TechVision Solutions', 'Nova Finance Group', 'MediCare Partners', 'RetailPro Inc',
            'BuildMax Manufacturing', 'EduSpark Academy', 'PropNest Realty', 'BrandBoost Agency',
            'FastTrack Logistics', 'TasteWave Foods', 'CloudCore Systems', 'SafeVault Banking',
            'HealthFirst Clinic', 'ShopEase Retail', 'SteelWorks Corp', 'LearnBridge Institute',
            'LandMark Properties', 'AdReach Marketing', 'SwiftMove Freight', 'GreenBite Organics',
            'DataFlow Analytics', 'ClearBank Solutions', 'WellPath Medical', 'TrendHub Store',
            'IronClad Industries', 'EduPath Online', 'CitySpace Realty', 'ClickBurst Digital',
            'CargoLink Express', 'FreshBowl Kitchen', 'ByteWave Technologies', 'PrimeLedger Finance',
            'CarePoint Hospital', 'SmartShelf Retail', 'MetalCraft Factory', 'BrightMind School',
            'SkyView Estates', 'PulseMedia Agency', 'TurboShip Courier', 'NatureFarm Foods',
            'SyncTech Labs', 'TrustFund Capital', 'MedAssist Center', 'ValueMart Chain',
            'ProBuild Systems', 'FutureLearn Hub', 'PrimeSpace Realty', 'ViralGrowth Agency',
            'RapidRoute Delivery', 'CrispBite Catering',
        ];

        foreach ($companyNames as $i => $name) {
            $id = DB::table('companies')->insertGetId([
                'name'           => $name,
                'industry'       => $industries[$i % count($industries)],
                'website'        => 'https://www.' . strtolower(str_replace([' ', '&'], ['-', ''], $name)) . '.com',
                'phone'          => '09' . rand(100000000, 999999999),
                'email'          => 'info@' . strtolower(str_replace([' ', '&'], ['-', ''], $name)) . '.com',
                'employees'      => rand(10, 500),
                'annual_revenue' => rand(500000, 50000000),
                'city'           => ['Cagayan de Oro', 'Davao', 'Cebu', 'Manila', 'Makati', 'Quezon City'][$i % 6],
                'country'        => 'Philippines',
                'address'        => rand(1, 999) . ' ' . ['Rizal', 'Mabini', 'Bonifacio', 'Luna', 'Aguinaldo'][$i % 5] . ' St.',
                'status'         => $statuses[$i % count($statuses)],
                'description'    => 'A leading company in the ' . $industries[$i % count($industries)] . ' sector.',
                'logo'           => null,
                'created_at'     => Carbon::now()->subDays(rand(1, 365)),
                'updated_at'     => now(),
                'deleted_at'     => null,
            ]);
            $companyIds[] = $id;
        }

        // -------------------------------------------------------
        // 3. CUSTOMERS (50 records)
        // -------------------------------------------------------
        $customerStatuses = ['customer', 'lead', 'prospect', 'inactive'];
        $firstNames = ['James','Maria','Carlo','Ana','Paolo','Liza','Rico','Jenny','Mark','Claire',
                       'Dave','Sofia','Luis','Nina','Ryan','Trisha','Ben','Carla','Fred','Grace',
                       'Hans','Ivy','Joel','Karen','Leo','Mia','Nick','Olive','Pete','Queenie',
                       'Ramon','Sara','Tony','Uma','Vic','Wendy','Xander','Ysa','Zeus','Alice',
                       'Brian','Cathy','Derek','Elena','Franz','Gina','Hugo','Iris','Jake','Kim'];
        $lastNames  = ['Santos','Reyes','Cruz','Lim','Garcia','Torres','Flores','Rivera','Gomez','Diaz',
                       'Bautista','Aquino','Dela Cruz','Ramos','Villanueva','Fernandez','Lopez','Mendoza','Castro','Gutierrez',
                       'Ortega','Vargas','Robles','Navarro','Morales','Jimenez','Aguilar','Perez','Castillo','Suarez',
                       'Herrera','Medina','Vega','Soto','Espinoza','Molina','Ruiz','Silva','Ibarra','Rios',
                       'Salazar','Fuentes','Pena','Miranda','Alvarado','Campos','Padilla','Guerrero','Serrano','Cano'];

        $customerIds = [];
        for ($i = 0; $i < 50; $i++) {
            $first = $firstNames[$i];
            $last  = $lastNames[$i];
            $id = DB::table('customers')->insertGetId([
                'first_name'       => $first,
                'last_name'        => $last,
                'email'            => strtolower($first . '.' . $last) . rand(1,99) . '@gmail.com',
                'phone'            => '09' . rand(100000000, 999999999),
                'company'          => $companyNames[$i],
                'address'          => rand(1, 999) . ' ' . ['Rizal St', 'Mabini Ave', 'Bonifacio Blvd', 'Luna Road', 'Aguinaldo Hwy'][$i % 5],
                'status'           => $customerStatuses[$i % count($customerStatuses)],
                'assigned_user_id' => $userIds[$i % count($userIds)],
                'created_at'       => Carbon::now()->subDays(rand(1, 365)),
                'updated_at'       => now(),
            ]);
            $customerIds[] = $id;
        }

        // -------------------------------------------------------
        // 4. CONTACTS (50 records)
        // -------------------------------------------------------
        $contactStatuses = ['active', 'inactive', 'prospect'];
        $jobTitles = ['CEO', 'CTO', 'Sales Manager', 'Marketing Head', 'HR Manager', 'Developer', 'Accountant', 'Operations Head', 'Project Manager', 'Business Analyst'];

        $contactIds = [];
        for ($i = 0; $i < 50; $i++) {
            $first = $firstNames[(50 - $i - 1) % 50];
            $last  = $lastNames[$i];
            $id = DB::table('contacts')->insertGetId([
                'first_name' => $first,
                'last_name'  => $last,
                'email'      => strtolower($first . '.' . $last) . rand(100, 999) . '@email.com',
                'phone'      => '09' . rand(100000000, 999999999),
                'job_title'  => $jobTitles[$i % count($jobTitles)],
                'company'    => $companyNames[$i],
                'status'     => $contactStatuses[$i % count($contactStatuses)],
                'notes'      => 'Contact note for ' . $first . ' ' . $last . '.',
                'avatar'     => null,
                'city'       => ['Cagayan de Oro', 'Davao', 'Cebu', 'Manila', 'Makati'][$i % 5],
                'country'    => 'Philippines',
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
            $contactIds[] = $id;
        }

        // -------------------------------------------------------
        // 5. LEADS (50 records)
        // -------------------------------------------------------
        $leadStatuses   = ['new', 'contacted', 'qualified', 'proposal_sent', 'negotiation', 'won', 'lost'];
        $leadPriorities = ['low', 'medium', 'high', 'urgent'];
        $leadSources    = ['website', 'referral', 'social_media', 'cold_call', 'email_campaign', 'event', 'other'];

        $leadIds = [];
        for ($i = 0; $i < 50; $i++) {
            $first = $firstNames[$i];
            $last  = $lastNames[(50 - $i - 1) % 50];
            $id = DB::table('leads')->insertGetId([
                'customer_id'      => $customerIds[$i],
                'name'             => $first . ' ' . $last,
                'email'            => strtolower($first . $last) . rand(1, 99) . '@lead.com',
                'phone'            => '09' . rand(100000000, 999999999),
                'source'           => $leadSources[$i % count($leadSources)],
                'status'           => $leadStatuses[$i % count($leadStatuses)],
                'priority'         => $leadPriorities[$i % count($leadPriorities)],
                'expected_value'   => rand(5000, 500000),
                'notes'            => 'Interested in our ' . $industries[$i % count($industries)] . ' solutions.',
                'assigned_user_id' => $userIds[$i % count($userIds)],
                'created_at'       => Carbon::now()->subDays(rand(1, 300)),
                'updated_at'       => now(),
            ]);
            $leadIds[] = $id;
        }

        // -------------------------------------------------------
        // 6. DEALS (50 records)
        // -------------------------------------------------------
        $dealStages = ['prospecting', 'qualification', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];
        $dealTitles = [
            'Enterprise License Agreement', 'Annual Support Contract', 'Software Implementation',
            'Consulting Package', 'Cloud Migration Project', 'Digital Transformation Deal',
            'System Upgrade Contract', 'Managed Services Agreement', 'Data Analytics Platform',
            'CRM Customization Project',
        ];

        $dealIds = [];
        for ($i = 0; $i < 50; $i++) {
            $stage      = $dealStages[$i % count($dealStages)];
            $closeDate  = Carbon::now()->addDays(rand(7, 180));
            $closedDate = in_array($stage, ['closed_won', 'closed_lost']) ? Carbon::now()->subDays(rand(1, 60)) : null;

            $id = DB::table('deals')->insertGetId([
                'customer_id'         => $customerIds[$i],
                'lead_id'             => $leadIds[$i],
                'title'               => $dealTitles[$i % count($dealTitles)] . ' #' . ($i + 1),
                'value'               => rand(10000, 2000000),
                'stage'               => $stage,
                'probability'         => rand(10, 100),
                'expected_close_date' => $closeDate->toDateString(),
                'closed_date'         => $closedDate ? $closedDate->toDateString() : null,
                'notes'               => 'Deal notes for client ' . $firstNames[$i] . ' ' . $lastNames[$i] . '.',
                'assigned_user_id'    => $userIds[$i % count($userIds)],
                'created_at'          => Carbon::now()->subDays(rand(1, 200)),
                'updated_at'          => now(),
            ]);
            $dealIds[] = $id;
        }

        // -------------------------------------------------------
        // 7. TASKS (50 records)
        // -------------------------------------------------------
        $taskStatuses    = ['pending', 'in_progress', 'completed', 'cancelled'];
        $taskPriorities  = ['low', 'medium', 'high'];
        $taskTitles      = [
            'Follow up with client', 'Prepare proposal document', 'Schedule demo call',
            'Send contract draft', 'Review deal requirements', 'Update CRM records',
            'Conduct needs analysis', 'Send onboarding materials', 'Check payment status',
            'Arrange site visit',
        ];

        for ($i = 0; $i < 50; $i++) {
            DB::table('tasks')->insert([
                'title'            => $taskTitles[$i % count($taskTitles)] . ' - ' . $firstNames[$i],
                'description'      => 'Task description for item ' . ($i + 1) . '.',
                'status'           => $taskStatuses[$i % count($taskStatuses)],
                'priority'         => $taskPriorities[$i % count($taskPriorities)],
                'due_date'         => Carbon::now()->addDays(rand(-10, 60))->toDateString(),
                'due_time'         => ['09:00', '10:00', '13:00', '14:00', '15:00'][$i % 5],
                'customer_id'      => $customerIds[$i],
                'lead_id'          => $leadIds[$i],
                'deal_id'          => $dealIds[$i],
                'assigned_user_id' => $userIds[$i % count($userIds)],
                'created_at'       => Carbon::now()->subDays(rand(1, 100)),
                'updated_at'       => now(),
            ]);
        }

        // -------------------------------------------------------
        // 8. FOLLOW UPS (50 records)
        // -------------------------------------------------------
        $followUpStatuses = ['pending', 'completed'];
        $followUpTitles   = [
            'Check in on proposal', 'Confirm meeting schedule', 'Send product brochure',
            'Follow up on payment', 'Discuss contract terms', 'Send demo recording',
            'Reconnect after holiday', 'Verify delivery status', 'Remind about renewal', 'Check satisfaction',
        ];

        for ($i = 0; $i < 50; $i++) {
            $status      = $followUpStatuses[$i % count($followUpStatuses)];
            $completedAt = $status === 'completed' ? Carbon::now()->subDays(rand(1, 30)) : null;

            DB::table('follow_ups')->insert([
                'customer_id'  => $customerIds[$i],
                'lead_id'      => $leadIds[$i],
                'user_id'      => $userIds[$i % count($userIds)],
                'title'        => $followUpTitles[$i % count($followUpTitles)],
                'description'  => 'Follow-up note for ' . $firstNames[$i] . ' regarding ' . $dealTitles[$i % count($dealTitles)] . '.',
                'due_date'     => Carbon::now()->addDays(rand(-5, 30)),
                'status'       => $status,
                'completed_at' => $completedAt,
                'created_at'   => Carbon::now()->subDays(rand(1, 90)),
                'updated_at'   => now(),
            ]);
        }

        // -------------------------------------------------------
        // 9. ACTIVITIES (50 records)
        // -------------------------------------------------------
        $activityTypes = ['call', 'email', 'meeting', 'note', 'task', 'follow_up', 'other'];
        $activityDescs = [
            'Had a productive call with the client about project scope.',
            'Sent follow-up email with pricing details.',
            'Meeting held to discuss implementation timeline.',
            'Added a note about client preferences.',
            'Completed task for document preparation.',
            'Followed up on pending contract approval.',
            'Miscellaneous activity logged.',
        ];

        for ($i = 0; $i < 50; $i++) {
            DB::table('activities')->insert([
                'customer_id'   => $customerIds[$i],
                'lead_id'       => $leadIds[$i],
                'user_id'       => $userIds[$i % count($userIds)],
                'activity_type' => $activityTypes[$i % count($activityTypes)],
                'description'   => $activityDescs[$i % count($activityDescs)],
                'activity_date' => Carbon::now()->subDays(rand(1, 180)),
                'created_at'    => Carbon::now()->subDays(rand(1, 180)),
                'updated_at'    => now(),
            ]);
        }

        // -------------------------------------------------------
        // 10. CALENDAR EVENTS (50 records)
        // -------------------------------------------------------
        $eventTypes   = ['meeting', 'call', 'demo', 'follow_up', 'deadline'];
        $eventColors  = ['#3788d8', '#28a745', '#dc3545', '#ffc107', '#6f42c1'];
        $eventTitles  = [
            'Client Discovery Call', 'Product Demo Session', 'Contract Review Meeting',
            'Sales Pipeline Review', 'Onboarding Kickoff', 'Quarterly Business Review',
            'Deal Closing Meeting', 'Support Follow-Up Call', 'Training Session', 'Team Sync',
        ];

        for ($i = 0; $i < 50; $i++) {
            $start  = Carbon::now()->addDays(rand(-30, 60))->setHour(rand(8, 16))->setMinute(0);
            $end    = (clone $start)->addHours(rand(1, 3));
            $allDay = ($i % 10 === 0);

            DB::table('calendar_events')->insert([
                'title'          => $eventTitles[$i % count($eventTitles)] . ' #' . ($i + 1),
                'description'    => 'Event description for ' . $firstNames[$i] . '.',
                'type'           => $eventTypes[$i % count($eventTypes)],
                'color'          => $eventColors[$i % count($eventColors)],
                'start_datetime' => $start,
                'end_datetime'   => $end,
                'all_day'        => $allDay,
                'location'       => ['Cagayan de Oro Office', 'Zoom Call', 'Client Site', 'Google Meet', 'Teams'][$i % 5],
                'contact_id'     => $contactIds[$i],
                'lead_id'        => $leadIds[$i],
                'deal_id'        => $dealIds[$i],
                'created_by'     => $userIds[$i % count($userIds)],
                'created_at'     => Carbon::now()->subDays(rand(1, 60)),
                'updated_at'     => now(),
                'deleted_at'     => null,
            ]);
        }

        $this->command->info('✅ Successfully seeded 50 records for all tables!');
    }
}