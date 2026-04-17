<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop soft deletes if exists
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        // Drop foreign key for converted_to_customer_id if it exists
        try {
            \DB::statement("ALTER TABLE leads DROP FOREIGN KEY leads_converted_to_customer_id_foreign");
        } catch (\Exception $e) {
            // Foreign key doesn't exist, continue
        }

        Schema::table('leads', function (Blueprint $table) {
            // Drop old columns that shouldn't be in the final schema
            $columnsToDrop = [];
            if (Schema::hasColumn('leads', 'lead_id')) $columnsToDrop[] = 'lead_id';
            if (Schema::hasColumn('leads', 'prospect_name')) $columnsToDrop[] = 'prospect_name';
            if (Schema::hasColumn('leads', 'contact_information')) $columnsToDrop[] = 'contact_information';
            if (Schema::hasColumn('leads', 'converted_at')) $columnsToDrop[] = 'converted_at';
            if (Schema::hasColumn('leads', 'converted_to_customer_id')) $columnsToDrop[] = 'converted_to_customer_id';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }

            // Add required columns if they don't exist
            if (!Schema::hasColumn('leads', 'name')) {
                $table->string('name')->after('customer_id');
            }
            if (!Schema::hasColumn('leads', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (!Schema::hasColumn('leads', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop the new columns we added
            $columnsToDrop = [];
            if (Schema::hasColumn('leads', 'name')) $columnsToDrop[] = 'name';
            if (Schema::hasColumn('leads', 'email')) $columnsToDrop[] = 'email';
            if (Schema::hasColumn('leads', 'phone')) $columnsToDrop[] = 'phone';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }

            // Restore old columns
            if (!Schema::hasColumn('leads', 'lead_id')) {
                $table->string('lead_id')->unique()->after('id');
            }
            if (!Schema::hasColumn('leads', 'prospect_name')) {
                $table->string('prospect_name')->after('customer_id');
            }
            if (!Schema::hasColumn('leads', 'contact_information')) {
                $table->string('contact_information')->after('prospect_name');
            }
            if (!Schema::hasColumn('leads', 'converted_at')) {
                $table->datetime('converted_at')->nullable();
            }
            if (!Schema::hasColumn('leads', 'converted_to_customer_id')) {
                $table->foreignId('converted_to_customer_id')->nullable()->constrained('customers')->onDelete('set null');
            }

            if (!Schema::hasColumn('leads', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }
};
