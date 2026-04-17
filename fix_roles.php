<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Step 1: Changing role column to VARCHAR...\n";
    DB::statement('ALTER TABLE users MODIFY COLUMN role VARCHAR(255)');
    echo "✓ Column changed to VARCHAR\n";

    echo "Step 2: Updating invalid role values...\n";
    $updated = DB::table('users')->whereNotIn('role', ['admin', 'manager', 'sales_staff'])->update(['role' => 'sales_staff']);
    echo "✓ Updated {$updated} invalid role(s)\n";

    echo "Step 3: Restoring enum constraint...\n";
    DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manager', 'sales_staff') DEFAULT 'sales_staff'");
    echo "✓ Enum constraint restored\n";

    echo "Step 4: Verifying data...\n";
    $users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
    echo "Current users:\n";
    foreach ($users as $user) {
        echo "- {$user->name} ({$user->email}): {$user->role}\n";
    }

    echo "\n🎉 Data fix completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}