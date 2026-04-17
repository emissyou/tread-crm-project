<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Only rename if the column exists
            if (Schema::hasColumn('tasks', 'contact_id')) {
                // Drop the old foreign key constraint if it exists
                try {
                    $table->dropForeign(['contact_id']);
                } catch (\Exception $e) {
                    // Foreign key may not exist
                }

                // Rename the column
                $table->renameColumn('contact_id', 'customer_id');

                // Add the new foreign key constraint
                $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['customer_id']);

            // Rename the column back
            $table->renameColumn('customer_id', 'contact_id');

            // Add the old foreign key constraint
            $table->foreign('contact_id')->references('id')->on('contacts')->nullOnDelete();
        });
    }
};