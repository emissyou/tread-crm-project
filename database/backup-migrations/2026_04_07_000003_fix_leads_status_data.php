<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update leads data to match new enum values before changing the column
        if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'status')) {
            // Map old values to new ones (valid values: 'new', 'contacted', 'negotiating', 'closed', 'lost')
            \DB::statement("UPDATE leads SET status = 'negotiating' WHERE status = 'qualified'");
            \DB::statement("UPDATE leads SET status = 'lost' WHERE status NOT IN ('new', 'contacted', 'negotiating', 'closed', 'lost')");
        }
    }

    public function down(): void
    {
        // Revert the changes if needed
        if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'status')) {
            \DB::statement("UPDATE leads SET status = 'negotiating' WHERE status = 'qualified'");
        }
    }
};
