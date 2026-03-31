<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Intentionally left empty.
        // Role column is fully defined in:
        // 2026_03_28_000007_add_fields_to_users_table.php
    }

    public function down(): void
    {
        // Nothing to roll back here.
    }
};