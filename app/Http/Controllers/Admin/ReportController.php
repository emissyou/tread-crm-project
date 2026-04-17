<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Activity;
use App\Models\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30');

        $startDate = now()->subDays((int)$period);

        // Summary Stats
        $summary = [
            'total_customers'  => Customer::count(),
            'new_customers'    => Customer::where('created_at', '>=', $startDate)->count(),
            'total_leads'      => Lead::count(),
            'new_leads'        => Lead::where('created_at', '>=', $startDate)->count(),
            'conversion_rate'  => Lead::count() > 0
                ? round((Lead::where('status', 'closed')->count() / Lead::count()) * 100, 1)
                : 0,
            'total_deals'      => Deal::count(),
            'total_revenue'    => Deal::sum('value'),
            'pipeline_value'   => Deal::sum('value'),
            'total_activities' => Activity::count(),
            'total_followups'  => FollowUp::count(),
        ];

        // Leads by Status (for pie chart)
        $leadsByStatus = Lead::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Leads by Source
        $leadsBySource = Lead::select('source', DB::raw('count(*) as count'))
            ->whereNotNull('source')
            ->groupBy('source')
            ->orderByDesc('count')
            ->get();

        // Leads by Priority
        $leadsByPriority = Lead::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get()
            ->keyBy('priority');

        // Deals by Stage
        $dealsByStage = Deal::select('stage', DB::raw('count(*) as count'))
            ->whereNotNull('stage')
            ->groupBy('stage')
            ->get();

        // Customers created per month (last 6 months)
        $monthlyCustomersRows = Customer::where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $monthlyCustomers = $this->buildMonthlySeries($monthlyCustomersRows, 'count');
        $monthlyContacts = $monthlyCustomers; // Alias for view compatibility

        // Revenue created per month (last 6 months)
        $monthlyRevenueRows = Deal::where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(value) as revenue')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $monthlyRevenue = $this->buildMonthlySeries($monthlyRevenueRows, 'revenue');

        // Recent Leads
        $recentLeads = Lead::with(['customer', 'assignedUser'])
            ->latest()
            ->limit(10)
            ->get();

        // Recent Activities
        $recentActivities = Activity::with(['customer', 'lead', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        // Recent Follow-ups
        $recentFollowUps = FollowUp::with(['customer', 'lead', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        // Recent Deals
        $recentDeals = Deal::with(['customer', 'lead'])
            ->latest()
            ->limit(10)
            ->get();

        // Top Customers by Deals
        $topCustomers = Customer::withCount('deals')
            ->orderByDesc('deals_count')
            ->limit(5)
            ->get()
            ->map(function ($customer) {
                $customer->total_value = $customer->deals()->sum('value');
                return $customer;
            });

        return view('admin.reports.index', compact(
            'summary', 'leadsByStatus', 'leadsBySource', 'leadsByPriority', 'dealsByStage',
            'monthlyCustomers', 'monthlyContacts', 'monthlyRevenue', 'recentLeads', 'recentActivities', 'recentFollowUps',
            'recentDeals', 'topCustomers', 'period'
        ));
    }

    private function buildMonthlySeries($rows, string $valueKey, int $months = 6): array
    {
        $series = [];
        $cursor = now()->startOfMonth()->subMonths($months - 1);

        for ($i = 0; $i < $months; $i++) {
            $label = $cursor->copy()->addMonths($i)->format('M Y');
            $series[$label] = [
                'label' => $label,
                $valueKey => 0,
            ];
        }

        foreach ($rows as $row) {
            $label = date('M Y', mktime(0, 0, 0, $row->month, 1, $row->year));
            if (isset($series[$label])) {
                $series[$label][$valueKey] = $valueKey === 'count' ? (int) $row->$valueKey : (float) $row->$valueKey;
            }
        }

        return array_values($series);
    }
}
