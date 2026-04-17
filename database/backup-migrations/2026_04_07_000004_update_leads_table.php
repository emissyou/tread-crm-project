<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('leads')) {
            return;
        }

        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'status')) {
                $table->enum('status', ['new', 'contacted', 'negotiating', 'closed', 'lost'])
                    ->default('new')
                    ->change();
            }
        });
    }

    public function down(): void
    {
        // Use raw SQL to safely drop foreign keys if they exist
        $foreignKeys = [
            'leads_customer_id_foreign',
            'leads_assigned_user_id_foreign',
            'leads_converted_to_customer_id_foreign'
        ];

        foreach ($foreignKeys as $fk) {
            try {
                \DB::statement("ALTER TABLE leads DROP FOREIGN KEY {$fk}");
            } catch (\Exception $e) {
                // Foreign key doesn't exist, continue
            }
        }

        Schema::table('leads', function (Blueprint $table) {
            // Drop columns - check if they exist
            $columnsToDrop = [];
            if (Schema::hasColumn('leads', 'lead_id')) $columnsToDrop[] = 'lead_id';
            if (Schema::hasColumn('leads', 'customer_id')) $columnsToDrop[] = 'customer_id';
            if (Schema::hasColumn('leads', 'prospect_name')) $columnsToDrop[] = 'prospect_name';
            if (Schema::hasColumn('leads', 'contact_information')) $columnsToDrop[] = 'contact_information';
            if (Schema::hasColumn('leads', 'assigned_user_id')) $columnsToDrop[] = 'assigned_user_id';
            if (Schema::hasColumn('leads', 'converted_at')) $columnsToDrop[] = 'converted_at';
            if (Schema::hasColumn('leads', 'converted_to_customer_id')) $columnsToDrop[] = 'converted_to_customer_id';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }

            // Add back old columns - check if they don't exist
            if (!Schema::hasColumn('leads', 'title')) {
                $table->string('title')->after('id');
            }
            if (!Schema::hasColumn('leads', 'contact_id')) {
                $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete()->after('title');
            }
            if (!Schema::hasColumn('leads', 'company_id')) {
                $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete()->after('contact_id');
            }
            if (!Schema::hasColumn('leads', 'follow_up_date')) {
                $table->date('follow_up_date')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('leads', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete()->after('follow_up_date');
            }

            // Revert column changes
            if (Schema::hasColumn('leads', 'expected_value')) {
                $table->renameColumn('expected_value', 'value');
            }
            $table->decimal('value', 15, 2)->nullable()->change();
            $table->enum('status', ['new', 'contacted', 'negotiating', 'closed', 'lost'])->default('new')->change();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->change();
        });
    }
};