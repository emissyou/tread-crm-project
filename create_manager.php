<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = \Illuminate\Http\Request::capture();
$kernel->handle($request);

// Create a manager user
$manager = \App\Models\User::create([
    'name' => 'Manager',
    'email' => 'manager@treadcrm.com',
    'password' => bcrypt('password'),
    'role' => 'manager',
]);

echo "Manager account created successfully!\n";
echo "Email: " . $manager->email . "\n";
echo "Password: password\n";
echo "Role: " . $manager->role . "\n";

// Verify it works
$checkManager = \App\Models\User::where('role', 'manager')->first();
echo "\nVerification:\n";
echo "Manager found: " . ($checkManager ? 'YES' : 'NO') . "\n";
echo "isManager(): " . ($checkManager->isManager() ? 'true' : 'false') . "\n";
echo "canDeleteCustomers(): " . ($checkManager->canDeleteCustomers() ? 'true' : 'false') . "\n";
echo "hasRestrictedAccess(): " . ($checkManager->hasRestrictedAccess() ? 'true' : 'false') . "\n";
