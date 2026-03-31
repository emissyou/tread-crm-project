<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\DealController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\ReportController;

Route::get('/', function () {
    return redirect('/login');
});

// Auth Routes
Route::get('/login', fn() => view('auth.login'))->name('login');

Route::post('/login', function () {
    $credentials = request()->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);
    if (auth()->attempt($credentials, request()->boolean('remember'))) {
        return redirect()->intended('/admin/dashboard');
    }
    return back()->withErrors(['email' => 'Invalid credentials.']);
})->name('login.store');

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            $now          = \Carbon\Carbon::now();
            $thisMonth    = $now->copy()->startOfMonth();
            $lastMonth    = $now->copy()->subMonth()->startOfMonth();
            $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

            // ── Helper closure for growth % ───────────────────────────────────
            $growth = function (int $old, int $new): int {
                if ($old === 0) return $new > 0 ? 100 : 0;
                return (int) round((($new - $old) / $old) * 100);
            };

            // ── Summary Counts ────────────────────────────────────────────────
            $totalContacts     = \App\Models\Contact::count();
            $contactsLastMonth = \App\Models\Contact::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
            $contactsThisMonth = \App\Models\Contact::where('created_at', '>=', $thisMonth)->count();

            $totalLeads     = \App\Models\Lead::count();
            $leadsLastMonth = \App\Models\Lead::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
            $leadsThisMonth = \App\Models\Lead::where('created_at', '>=', $thisMonth)->count();

            $totalDeals     = \App\Models\Deal::count();
            $dealsLastMonth = \App\Models\Deal::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
            $dealsThisMonth = \App\Models\Deal::where('created_at', '>=', $thisMonth)->count();

            $totalRevenue     = \App\Models\Deal::where('stage', 'closed_won')->sum('value');
            $revenueLastMonth = \App\Models\Deal::where('stage', 'closed_won')
                                    ->whereBetween('updated_at', [$lastMonth, $lastMonthEnd])->sum('value');
            $revenueThisMonth = \App\Models\Deal::where('stage', 'closed_won')
                                    ->where('updated_at', '>=', $thisMonth)->sum('value');

            $stats = [
                'totalContacts'  => $totalContacts,
                'contactsGrowth' => $growth($contactsLastMonth, $contactsThisMonth),
                'totalLeads'     => $totalLeads,
                'leadsGrowth'    => $growth($leadsLastMonth, $leadsThisMonth),
                'totalDeals'     => $totalDeals,
                'dealsGrowth'    => $growth($dealsLastMonth, $dealsThisMonth),
                'totalRevenue'   => $totalRevenue,
                'revenueGrowth'  => $growth((int) $revenueLastMonth, (int) $revenueThisMonth),
            ];

            // ── Leads Pipeline ────────────────────────────────────────────────
            $stageCounts = \App\Models\Lead::select('status', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                               ->groupBy('status')
                               ->pluck('count', 'status')
                               ->toArray();

            $closedLeads    = $stageCounts['closed'] ?? 0;
            $conversionRate = $totalLeads > 0 ? round(($closedLeads / $totalLeads) * 100) : 0;

            $pipeline = [
                'conversionRate' => $conversionRate,
                'closedLeads'    => $closedLeads,
                'stages'         => collect(['new', 'contacted', 'negotiating', 'closed', 'lost'])
                                        ->map(fn ($s) => ['status' => $s, 'count' => $stageCounts[$s] ?? 0])
                                        ->all(),
            ];

            // ── Monthly Revenue Chart (last 6 months) ─────────────────────────
            // FIX: use created_at instead of updated_at so seeded historical
            //      dates are matched correctly by month
            $chartLabels = [];
            $chartValues = [];
            for ($i = 5; $i >= 0; $i--) {
                $date          = $now->copy()->subMonths($i);
                $chartLabels[] = $date->format('M Y');
                $chartValues[] = (float) \App\Models\Deal::where('stage', 'closed_won')
                                             ->whereYear('created_at', $date->year)
                                             ->whereMonth('created_at', $date->month)
                                             ->sum('value');
            }
            $chartData = ['labels' => $chartLabels, 'values' => $chartValues];

            // ── Recent Activities ─────────────────────────────────────────────
            $recentContacts = \App\Models\Contact::select('id', 'first_name', 'last_name', 'created_at')
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

            $recentLeads = \App\Models\Lead::select('id', 'title', 'created_at')
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

            $recentDeals = \App\Models\Deal::select('id', 'title', 'value', 'created_at')
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

            // ── Upcoming Tasks ────────────────────────────────────────────────
            $upcomingTasks = \App\Models\Task::with(['contact', 'lead'])
                                 ->where('status', '!=', 'completed')
                                 ->where('due_date', '>=', now()->startOfDay())
                                 ->orderBy('due_date')
                                 ->take(5)
                                 ->get();

            // ── Top Contacts by Deal Value ─────────────────────────────────────
            // FIX: removed ->with('company') — contacts table has no company_id FK,
            //      only a plain 'company' string column. Use $contact->company directly.
            $topContacts = \App\Models\Contact::withSum('deals', 'value')
                               ->withCount('deals')
                               ->having('deals_sum_value', '>', 0)
                               ->orderByDesc('deals_sum_value')
                               ->take(4)
                               ->get();

            return view('admin.dashboard', compact(
                'stats', 'pipeline', 'chartData',
                'recentActivities', 'upcomingTasks', 'topContacts'
            ));
        })->name('dashboard');

        // Contacts
        Route::get('/contacts/export', [ContactController::class, 'exportCsv'])->name('contacts.export');
        Route::resource('/contacts', ContactController::class)->except(['create', 'edit']);

        // Companies
        Route::resource('/companies', CompanyController::class)->except(['create', 'edit']);

        // Leads
        Route::resource('/leads', LeadController::class)->except(['create', 'edit']);

        // Deals
        Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.updateStage');
        Route::resource('/deals', DealController::class)->except(['create', 'edit']);

        // Tasks
        Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])->name('tasks.toggle');
        Route::resource('/tasks', TaskController::class)->except(['create', 'edit']);

        // Calendar
        Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
        Route::resource('/calendar', CalendarController::class)->except(['create', 'edit'])->parameters(['calendar' => 'calendarEvent']);

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });