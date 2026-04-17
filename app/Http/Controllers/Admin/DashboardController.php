<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now          = Carbon::now();
        $thisMonth    = $now->copy()->startOfMonth();
        $lastMonth    = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // ── Summary Stats ─────────────────────────
        $totalCustomers     = Customer::count();
        $customersLastMonth = Customer::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $customersThisMonth = Customer::where('created_at', '>=', $thisMonth)->count();
        $customersGrowth    = $this->growthPercent($customersLastMonth, $customersThisMonth);

        $totalLeads     = Lead::count();
        $leadsLastMonth = Lead::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $leadsThisMonth = Lead::where('created_at', '>=', $thisMonth)->count();
        $leadsGrowth    = $this->growthPercent($leadsLastMonth, $leadsThisMonth);

        $totalDeals     = Deal::count();
        $dealsLastMonth = Deal::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $dealsThisMonth = Deal::where('created_at', '>=', $thisMonth)->count();
        $dealsGrowth    = $this->growthPercent($dealsLastMonth, $dealsThisMonth);

        $totalRevenue     = Deal::where('stage', 'closed_won')->sum('value');
        $revenueLastMonth = Deal::where('stage', 'closed_won')
                               ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                               ->sum('value');
        $revenueThisMonth = Deal::where('stage', 'closed_won')
                               ->where('created_at', '>=', $thisMonth)
                               ->sum('value');
        $revenueGrowth    = $this->growthPercent($revenueLastMonth, $revenueThisMonth);

        $stats = compact(
            'totalCustomers', 'customersGrowth',
            'totalLeads',    'leadsGrowth',
            'totalDeals',    'dealsGrowth',
            'totalRevenue',  'revenueGrowth'
        );

        // ── Leads Pipeline ─────────────────────────
        $stageCounts = Lead::select('status', DB::raw('count(*) as count'))
                           ->groupBy('status')
                           ->pluck('count', 'status')
                           ->toArray();

        $stageOrder = ['new', 'contacted', 'qualified', 'proposal_sent', 'negotiation', 'won', 'lost'];

        $stages = collect($stageOrder)->map(fn ($s) => [
            'status' => $s,
            'count'  => $stageCounts[$s] ?? 0,
        ])->all();

        $closedLeads    = $stageCounts['won'] ?? 0;
        $conversionRate = $totalLeads > 0 ? round(($closedLeads / $totalLeads) * 100) : 0;

        $pipeline = compact('stages', 'closedLeads', 'conversionRate');

        // ── Monthly Revenue Chart ─────────────────────────
        $chartData = $this->getMonthlyRevenue(6);

        // ── Recent Activities ─────────────────────────
        $recentCustomers = Customer::latest()->take(2)->get()
            ->map(fn ($c) => [
                'title'      => 'New customer added',
                'subtitle'   => $c->full_name,
                'time'       => $c->created_at,
                'icon'       => 'fas fa-user-plus',
                'iconBg'     => 'bg-success bg-opacity-20 text-success',
                'badge'      => 'Customer',
                'badgeClass' => 'bg-success',
            ]);

        $recentActivitiesData = Activity::with(['customer', 'lead', 'createdBy'])
            ->latest('activity_date')
            ->take(4)
            ->get()
            ->map(fn ($a) => [
                'title'      => $a->getActivityTypeLabelAttribute(),
                'subtitle'   => $a->customer?->full_name ?? $a->lead?->name ?? 'Unknown',
                'time'       => $a->activity_date,
                'icon'       => $a->getIconAttribute(),
                'iconBg'     => 'bg-' . $a->getColorAttribute() . ' bg-opacity-20 text-' . $a->getColorAttribute(),
                'badge'      => $a->activity_type,
                'badgeClass' => 'bg-' . $a->getColorAttribute(),
            ]);

        $recentLeads = Lead::latest()->take(3)->get()
            ->map(fn ($l) => [
                'title'      => 'New lead created',
                'subtitle'   => $l->name,
                'time'       => $l->created_at,
                'icon'       => 'fas fa-bullseye',
                'iconBg'     => 'bg-primary bg-opacity-20 text-primary',
                'badge'      => 'Lead',
                'badgeClass' => 'bg-warning',
            ]);

        $recentDeals = Deal::where('stage', 'closed_won')
            ->latest()
            ->take(3)
            ->get()
            ->map(fn ($d) => [
                'title'      => 'Deal won',
                'subtitle'   => '₱' . number_format($d->value) . ' • ' . $d->title,
                'time'       => $d->created_at,
                'icon'       => 'fas fa-handshake',
                'iconBg'     => 'bg-info bg-opacity-20 text-info',
                'badge'      => '₱' . number_format($d->value),
                'badgeClass' => 'bg-success',
            ]);

        $recentActivities = $recentCustomers
            ->concat($recentLeads)
            ->concat($recentDeals)
            ->concat($recentActivitiesData)
            ->sortByDesc('time')
            ->take(6)
            ->values();

        // ── Upcoming Tasks ─────────────────────────
        $upcomingTasks = Task::with(['customer', 'lead'])
            ->whereIn('status', ['pending', 'in_progress'])
            ->where('due_date', '>=', now()->startOfDay())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // ── Top Customers ─────────────────────────
        $topCustomers = Customer::withSum('deals', 'value')
            ->withCount('deals')
            ->having('deals_sum_value', '>', 0)
            ->orderByDesc('deals_sum_value')
            ->take(4)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'pipeline',
            'chartData',
            'recentActivities',
            'upcomingTasks',
            'topCustomers'
        ));
    }

    private function growthPercent(float $old, float $new): int
    {
        if ($old == 0) return $new > 0 ? 100 : 0;
        return (int) round((($new - $old) / $old) * 100);
    }

    private function getMonthlyRevenue(int $months = 6): array
    {
        $labels = [];
        $values = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date     = now()->subMonths($i);
            $labels[] = $date->format('M Y');

            $values[] = (float) Deal::where('stage', 'closed_won')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('value');
        }

        return compact('labels', 'values');
    }
}