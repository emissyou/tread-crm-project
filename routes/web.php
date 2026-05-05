<?php

use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\FollowUpController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DealController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return redirect('/login');
});

// ====================== AUTH ROUTES ======================
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

// ====================== ADMIN ROUTES ======================
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])
            ->middleware('manager')
            ->name('reports.index');

        // Customers
        Route::get('/customers', [CustomerController::class, 'index'])
            ->middleware('sales_staff')
            ->name('customers.index');

        Route::get('/customers/export', [CustomerController::class, 'exportCsv'])
            ->middleware('manager')
            ->name('customers.export');

        Route::get('/customers/create', [CustomerController::class, 'create'])
            ->middleware('manager')
            ->name('customers.create');

        Route::get('/customers/{customer}', [CustomerController::class, 'show'])
            ->middleware('sales_staff')
            ->name('customers.show');

        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])
            ->middleware('manager')
            ->name('customers.edit');

        Route::post('/customers', [CustomerController::class, 'store'])
            ->middleware('manager')
            ->name('customers.store');

        Route::patch('/customers/{customer}', [CustomerController::class, 'update'])
            ->middleware('manager')
            ->name('customers.update');

        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
            ->middleware('manager')
            ->name('customers.destroy');

        // Companies
        Route::middleware('manager')->group(function () {
            Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
            Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
            Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
            Route::patch('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
            Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        });

        // Leads
        Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
        Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
        Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
        Route::patch('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

        // Deals
        Route::middleware('manager')->group(function () {
            Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.updateStage');
            Route::get('/deals', [DealController::class, 'index'])->name('deals.index');
            Route::post('/deals', [DealController::class, 'store'])->name('deals.store');
            Route::get('/deals/{deal}', [DealController::class, 'show'])->name('deals.show');
            Route::patch('/deals/{deal}', [DealController::class, 'update'])->name('deals.update');
            Route::delete('/deals/{deal}', [DealController::class, 'destroy'])->name('deals.destroy');
        });

        // Tasks
        Route::middleware('sales_staff')->group(function () {
            Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
            Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
            Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
            Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
            Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
            Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])->name('tasks.toggle');
        });

        // Follow-ups
        Route::middleware('sales_staff')->group(function () {
            Route::get('/follow-ups', [FollowUpController::class, 'index'])->name('follow-ups.index');
            Route::post('/follow-ups', [FollowUpController::class, 'store'])->name('follow-ups.store');
            Route::get('/follow-ups/{followUp}', [FollowUpController::class, 'show'])->name('follow-ups.show');
            Route::patch('/follow-ups/{followUp}', [FollowUpController::class, 'update'])->name('follow-ups.update');
            Route::delete('/follow-ups/{followUp}', [FollowUpController::class, 'destroy'])->name('follow-ups.destroy');
            Route::patch('/follow-ups/{followUp}/toggle', [FollowUpController::class, 'toggleComplete'])->name('follow-ups.toggle');
        });

        // Activities
        Route::middleware('sales_staff')->group(function () {
            Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
            Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
            Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
            Route::patch('/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
            Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');

            Route::get('/activities/customer/{customer}', [ActivityController::class, 'getForCustomer'])->name('activities.customer');
            Route::get('/activities/lead/{lead}', [ActivityController::class, 'getForLead'])->name('activities.lead');
        });

        // ====================== USER MANAGEMENT (Admin Only) ======================
        Route::middleware('admin')->group(function () {

            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');

            Route::post('/users/{id}/archive', [UserController::class, 'archive'])
                ->name('users.archive');

            Route::get('/users/archived', [UserController::class, 'archived'])
                ->name('users.archived');

            Route::post('/users/{id}/restore', [UserController::class, 'restore'])
                ->name('users.restore');

            Route::get('/users/{user}', [UserController::class, 'show'])
                ->whereNumber('user')
                ->name('users.show');
            Route::patch('/users/{user}', [UserController::class, 'update'])
                ->whereNumber('user')
                ->name('users.update');
        });

        // Settings
        Route::get('/settings', fn() => view('admin.settings.index'))
            ->middleware('admin')
            ->name('settings.index');

    });  // ← This closes the main admin group