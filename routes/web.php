<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomerController;use App\Http\Controllers\Admin\ReportController;use App\Http\Controllers\Admin\FollowUpController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DealController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\LeadController;

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
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {




        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Reports - All authenticated users can view
        Route::get('/reports', [ReportController::class, 'index'])->middleware('sales_staff')->name('reports.index');

        // Customers - Admin and Manager can manage, Sales Staff can view
        Route::get('/customers', [CustomerController::class, 'index'])->middleware('sales_staff')->name('customers.index');
        Route::get('/customers/export', [CustomerController::class, 'exportCsv'])->middleware('manager')->name('customers.export');
        Route::get('/customers/create', [CustomerController::class, 'create'])->middleware('manager')->name('customers.create');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->middleware('sales_staff')->name('customers.show');
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->middleware('manager')->name('customers.edit');
        Route::post('/customers', [CustomerController::class, 'store'])->middleware('manager')->name('customers.store');
        Route::patch('/customers/{customer}', [CustomerController::class, 'update'])->middleware('manager')->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->middleware('manager')->name('customers.destroy');
        

        // Companies - Admin and Manager only
        Route::get('/companies', [CompanyController::class, 'index'])->middleware('manager')->name('companies.index');
        Route::post('/companies', [CompanyController::class, 'store'])->middleware('manager')->name('companies.store');
        Route::get('/companies/{company}', [CompanyController::class, 'show'])->middleware('manager')->name('companies.show');
        Route::patch('/companies/{company}', [CompanyController::class, 'update'])->middleware('manager')->name('companies.update');
        Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->middleware('manager')->name('companies.destroy');


        // ==================== LEADS ROUTES - FIXED ====================
        Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
        Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
        Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
        Route::patch('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

        // Deals - Admin and Manager only
        Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->middleware('manager')->name('deals.updateStage');
        Route::get('/deals', [DealController::class, 'index'])->middleware('manager')->name('deals.index');
        Route::post('/deals', [DealController::class, 'store'])->middleware('manager')->name('deals.store');
        Route::get('/deals/{deal}', [DealController::class, 'show'])->middleware('manager')->name('deals.show');
        Route::patch('/deals/{deal}', [DealController::class, 'update'])->middleware('manager')->name('deals.update');
        Route::delete('/deals/{deal}', [DealController::class, 'destroy'])->middleware('manager')->name('deals.destroy');

        // Tasks - All roles
        Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])->middleware('sales_staff')->name('tasks.toggle');
        Route::get('/tasks', [TaskController::class, 'index'])->middleware('sales_staff')->name('tasks.index');
        Route::post('/tasks', [TaskController::class, 'store'])->middleware('sales_staff')->name('tasks.store');
        Route::get('/tasks/{task}', [TaskController::class, 'show'])->middleware('sales_staff')->name('tasks.show');
        Route::patch('/tasks/{task}', [TaskController::class, 'update'])->middleware('sales_staff')->name('tasks.update');
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->middleware('sales_staff')->name('tasks.destroy');

        // Follow-ups - All roles
        Route::patch('/follow-ups/{followUp}/toggle', [FollowUpController::class, 'toggleComplete'])->middleware('sales_staff')->name('follow-ups.toggle');
        Route::get('/follow-ups', [FollowUpController::class, 'index'])->middleware('sales_staff')->name('follow-ups.index');
        Route::post('/follow-ups', [FollowUpController::class, 'store'])->middleware('sales_staff')->name('follow-ups.store');
        Route::get('/follow-ups/{followUp}', [FollowUpController::class, 'show'])->middleware('sales_staff')->name('follow-ups.show');
        Route::patch('/follow-ups/{followUp}', [FollowUpController::class, 'update'])->middleware('sales_staff')->name('follow-ups.update');
        Route::delete('/follow-ups/{followUp}', [FollowUpController::class, 'destroy'])->middleware('sales_staff')->name('follow-ups.destroy');

        // Activities - All roles
        Route::get('/activities/customer/{customer}', [ActivityController::class, 'getForCustomer'])->middleware('sales_staff')->name('activities.customer');
        Route::get('/activities/lead/{lead}', [ActivityController::class, 'getForLead'])->middleware('sales_staff')->name('activities.lead');
        Route::get('/activities', [ActivityController::class, 'index'])->middleware('sales_staff')->name('activities.index');
        Route::post('/activities', [ActivityController::class, 'store'])->middleware('sales_staff')->name('activities.store');
        Route::get('/activities/{activity}', [ActivityController::class, 'show'])->middleware('sales_staff')->name('activities.show');
        Route::patch('/activities/{activity}', [ActivityController::class, 'update'])->middleware('sales_staff')->name('activities.update');
        Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->middleware('sales_staff')->name('activities.destroy');

        // User management - Admin only
        Route::middleware('admin')->group(function () {
            Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
            Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
            Route::patch('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
            Route::patch('/users/{user}/toggle-active', [App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('users.toggle-active');
        });

        // Settings - Admin only
        Route::get('/settings', fn() => view('admin.settings.index'))->middleware('admin')->name('settings.index');
    });