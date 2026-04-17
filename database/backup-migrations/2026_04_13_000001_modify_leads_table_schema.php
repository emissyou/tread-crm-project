<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('leads', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('leads', 'contact_id')) {
                $table->dropForeign(['contact_id']);
                $table->dropColumn('contact_id');
            }
            if (Schema::hasColumn('leads', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }
            if (Schema::hasColumn('leads', 'follow_up_date')) {
                $table->dropColumn('follow_up_date');
            }
            if (Schema::hasColumn('leads', 'value')) {
                $table->dropColumn('value');
            }
            if (Schema::hasColumn('leads', 'assigned_to')) {
                $table->dropForeign(['assigned_to']);
                $table->dropColumn('assigned_to');
            }
            if (Schema::hasColumn('leads', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('leads', function (Blueprint $table) {
            // Add new columns
            if (!Schema::hasColumn('leads', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->after('id')->constrained('customers')->nullOnDelete();
            }
            if (!Schema::hasColumn('leads', 'name')) {
                $table->string('name')->after('customer_id');
            }
            if (!Schema::hasColumn('leads', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (!Schema::hasColumn('leads', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('leads', 'expected_value')) {
                $table->decimal('expected_value', 15, 2)->nullable()->after('priority');
            }
            if (!Schema::hasColumn('leads', 'assigned_user_id')) {
                $table->foreignId('assigned_user_id')->nullable()->after('notes')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'customer_id')) {
                $table->dropForeign(['customer_id']);
                $table->dropColumn('customer_id');
            }
            if (Schema::hasColumn('leads', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('leads', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('leads', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('leads', 'expected_value')) {
                $table->dropColumn('expected_value');
            }
            if (Schema::hasColumn('leads', 'assigned_user_id')) {
                $table->dropForeign(['assigned_user_id']);
                $table->dropColumn('assigned_user_id');
            }
        });
    }
};
