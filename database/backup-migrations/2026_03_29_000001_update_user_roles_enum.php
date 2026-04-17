<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Handle any existing invalid role values by updating them to valid ones
        \DB::table('users')->where('role', 'agent')->update(['role' => 'sales_staff']);
        \DB::table('users')->whereNotIn('role', ['admin', 'manager', 'sales_staff'])->update(['role' => 'sales_staff']);

        // Ensure the enum is correctly defined (it should already be from previous migration)
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'manager', 'sales_staff'])->default('sales_staff')->change();
        });
    }

    public function down(): void
    {
        // This migration only ensures data consistency and correct enum definition
        // No rollback needed as the enum values are the correct ones
    }
};