<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Company;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Task;
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
            'total_contacts'  => Contact::count(),
            'new_contacts'    => Contact::where('created_at', '>=', $startDate)->count(),
            'total_leads'     => Lead::count(),
            'new_leads'       => Lead::where('created_at', '>=', $startDate)->count(),
            'conversion_rate' => Lead::count() > 0
                ? round((Lead::where('status', 'closed')->count() / Lead::count()) * 100, 1)
                : 0,
            'total_deals'     => Deal::count(),
            'total_revenue'   => Deal::where('stage', 'closed_won')->sum('value'),
            'pipeline_value'  => Deal::whereNotIn('stage', ['closed_won', 'closed_lost'])->sum('value'),
            'won_deals'       => Deal::where('stage', 'closed_won')->count(),
            'lost_deals'      => Deal::where('stage', 'closed_lost')->count(),
            'tasks_completed' => Task::where('status', 'completed')->count(),
            'tasks_overdue'   => Task::where('status', '!=', 'completed')->where('due_date', '<', now())->count(),
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

        // Deals by Stage
        $dealsByStage = Deal::select('stage', DB::raw('count(*) as count'), DB::raw('sum(value) as total_value'))
            ->groupBy('stage')
            ->get();

        // Monthly Revenue (last 6 months)
        $monthlyRevenue = Deal::where('stage', 'closed_won')
            ->where('closed_date', '>=', now()->subMonths(6))
            ->select(
                DB::raw('YEAR(closed_date) as year'),
                DB::raw('MONTH(closed_date) as month'),
                DB::raw('sum(value) as revenue')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($row) {
                return [
                    'label'   => date('M Y', mktime(0, 0, 0, $row->month, 1, $row->year)),
                    'revenue' => (float) $row->revenue,
                ];
            });

        // Contacts created per month (last 6 months)
        $monthlyContacts = Contact::where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($row) {
                return [
                    'label' => date('M Y', mktime(0, 0, 0, $row->month, 1, $row->year)),
                    'count' => (int) $row->count,
                ];
            });

        // Top Contacts by Deals
        $topContacts = Contact::withCount('deals')
            ->withSum(['deals as total_value' => fn($q) => $q->where('stage', 'closed_won')], 'value')
            ->orderByDesc('deals_count')
            ->limit(5)
            ->get();

        // Recent Deals
        $recentDeals = Deal::with(['contact', 'company'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact(
            'summary', 'leadsByStatus', 'leadsBySource',
            'dealsByStage', 'monthlyRevenue', 'monthlyContacts',
            'topContacts', 'recentDeals', 'period'
        ));
    }
}
