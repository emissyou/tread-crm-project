<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // This migration has been moved to 2026_04_07_000005_rename_contact_id_to_customer_id_in_tasks_table.php
        // to run after the customers table is created. This file is kept for migration history.
    }

    public function down(): void
    {
        // This migration has been moved to 2026_04_07_000005_rename_contact_id_to_customer_id_in_tasks_table.php
        // No rollback needed here.
    }
};