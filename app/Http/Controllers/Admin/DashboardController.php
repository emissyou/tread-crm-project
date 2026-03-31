<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
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

        // ── Summary Stats ─────────────────────────────────────────────────────

        $totalContacts     = Contact::count();
        $contactsLastMonth = Contact::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $contactsThisMonth = Contact::where('created_at', '>=', $thisMonth)->count();
        $contactsGrowth    = $this->growthPercent($contactsLastMonth, $contactsThisMonth);

        $totalLeads     = Lead::count();
        $leadsLastMonth = Lead::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $leadsThisMonth = Lead::where('created_at', '>=', $thisMonth)->count();
        $leadsGrowth    = $this->growthPercent($leadsLastMonth, $leadsThisMonth);

        $totalDeals     = Deal::count();
        $dealsLastMonth = Deal::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $dealsThisMonth = Deal::where('created_at', '>=', $thisMonth)->count();
        $dealsGrowth    = $this->growthPercent($dealsLastMonth, $dealsThisMonth);

        // FIX: changed 'status' => 'won'  to  'stage' => 'closed_won'
        $totalRevenue     = Deal::where('stage', 'closed_won')->sum('value');
        $revenueLastMonth = Deal::where('stage', 'closed_won')
                               ->whereBetween('updated_at', [$lastMonth, $lastMonthEnd])
                               ->sum('value');
        $revenueThisMonth = Deal::where('stage', 'closed_won')
                               ->where('updated_at', '>=', $thisMonth)
                               ->sum('value');
        $revenueGrowth    = $this->growthPercent($revenueLastMonth, $revenueThisMonth);

        $stats = compact(
            'totalContacts', 'contactsGrowth',
            'totalLeads',    'leadsGrowth',
            'totalDeals',    'dealsGrowth',
            'totalRevenue',  'revenueGrowth'
        );

        // ── Leads Pipeline ────────────────────────────────────────────────────

        $stageCounts = Lead::select('status', DB::raw('count(*) as count'))
                           ->groupBy('status')
                           ->pluck('count', 'status')
                           ->toArray();

        $stageOrder = ['new', 'contacted', 'negotiating', 'closed', 'lost'];

        $stages = collect($stageOrder)->map(fn ($s) => [
            'status' => $s,
            'count'  => $stageCounts[$s] ?? 0,
        ])->all();

        $closedLeads    = $stageCounts['closed'] ?? 0;
        $conversionRate = $totalLeads > 0
            ? round(($closedLeads / $totalLeads) * 100)
            : 0;

        $pipeline = compact('stages', 'closedLeads', 'conversionRate');

        // ── Monthly Revenue Chart (last 6 months) ─────────────────────────────

        $chartData = $this->getMonthlyRevenue(6);

        // ── Recent Activities ─────────────────────────────────────────────────

        $recentContacts = Contact::select('id', 'first_name', 'last_name', 'created_at')
                                 ->latest()->take(3)->get()
                                 ->map(fn ($c) => [
                                     'title'      => 'New contact added',
                                     'subtitle'   => trim($c->first_name . ' ' . $c->last_name),
                                     'time'       => $c->created_at,
                                     'icon'       => 'fas fa-user-plus',
                                     'iconBg'     => 'bg-success bg-opacity-20 text-success',
                                     'badge'      => 'Contact',
                                     'badgeClass' => 'bg-success',
                                 ]);

        // FIX: changed 'name' to 'title' to match the Lead model/seeder field
        $recentLeads = Lead::select('id', 'title', 'created_at')
                           ->latest()->take(3)->get()
                           ->map(fn ($l) => [
                               'title'      => 'New lead created',
                               'subtitle'   => $l->title,
                               'time'       => $l->created_at,
                               'icon'       => 'fas fa-bullseye',
                               'iconBg'     => 'bg-primary bg-opacity-20 text-primary',
                               'badge'      => 'Lead',
                               'badgeClass' => 'bg-warning',
                           ]);

        // FIX: changed 'status' => 'won'  to  'stage' => 'closed_won'
        $recentDeals = Deal::select('id', 'title', 'value', 'created_at')
                           ->where('stage', 'closed_won')
                           ->latest()->take(3)->get()
                           ->map(fn ($d) => [
                               'title'      => 'Deal won',
                               'subtitle'   => '$' . number_format($d->value) . ' • ' . $d->title,
                               'time'       => $d->created_at,
                               'icon'       => 'fas fa-handshake',
                               'iconBg'     => 'bg-info bg-opacity-20 text-info',
                               'badge'      => '$' . number_format($d->value),
                               'badgeClass' => 'bg-success',
                           ]);

        $recentActivities = $recentContacts
            ->concat($recentLeads)
            ->concat($recentDeals)
            ->sortByDesc('time')
            ->take(6)
            ->values();

        // ── Upcoming Tasks ────────────────────────────────────────────────────

        $upcomingTasks = Task::with(['contact', 'lead'])
                             ->whereIn('status', ['pending', 'in_progress'])
                             ->where('due_date', '>=', now()->startOfDay())
                             ->orderBy('due_date')
                             ->take(5)
                             ->get();

        // ── Top Contacts by Deal Value ─────────────────────────────────────────

        $topContacts = Contact::withSum('deals', 'value')
                              ->withCount('deals')
                              ->with('company')
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
            'topContacts'
        ));
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function growthPercent(float $old, float $new): int
    {
        if ($old == 0) {
            return $new > 0 ? 100 : 0;
        }

        return (int) round((($new - $old) / $old) * 100);
    }

    private function getMonthlyRevenue(int $months = 6): array
    {
        $labels = [];
        $values = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date     = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            // FIX: changed 'status' => 'won'  to  'stage' => 'closed_won'
            $values[] = (float) Deal::where('stage', 'closed_won')
                                    ->whereYear('updated_at', $date->year)
                                    ->whereMonth('updated_at', $date->month)
                                    ->sum('value');
        }

        return compact('labels', 'values');
    }
}