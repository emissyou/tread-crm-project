<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = \Illuminate\Http\Request::capture();
$kernel->handle($request);

// Check for manager users
$users = \App\Models\User::all();
echo "Total users: " . count($users) . "\n\n";

foreach ($users as $user) {
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    echo "---\n";
}

// Test if User model methods work
$manager = \App\Models\User::where('role', 'manager')->first();
if ($manager) {
    echo "\nTesting manager user: " . $manager->name . "\n";
    echo "isManager(): " . ($manager->isManager() ? 'true' : 'false') . "\n";
    echo "canDeleteCustomers(): " . ($manager->canDeleteCustomers() ? 'true' : 'false') . "\n";
    echo "hasRestrictedAccess(): " . ($manager->hasRestrictedAccess() ? 'true' : 'false') . "\n";
} else {
    echo "\nNo manager user found!\n";
}
