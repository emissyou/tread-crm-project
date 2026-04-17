<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Drop soft deletes
            if (Schema::hasColumn('customers', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            
            // Drop unnecessary columns
            if (Schema::hasColumn('customers', 'customer_id')) {
                $table->dropColumn('customer_id');
            }
            if (Schema::hasColumn('customers', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('customers', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('customers', 'country')) {
                $table->dropColumn('country');
            }
            if (Schema::hasColumn('customers', 'job_title')) {
                $table->dropColumn('job_title');
            }
            
            // Rename company_name to company
            if (Schema::hasColumn('customers', 'company_name')) {
                $table->renameColumn('company_name', 'company');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'deleted_at')) {
                $table->softDeletes();
            }
            
            if (!Schema::hasColumn('customers', 'customer_id')) {
                $table->string('customer_id')->unique()->after('id');
            }
            if (!Schema::hasColumn('customers', 'company')) {
                $table->renameColumn('company', 'company_name');
            }
            if (!Schema::hasColumn('customers', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('customers', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('customers', 'country')) {
                $table->string('country')->nullable();
            }
            if (!Schema::hasColumn('customers', 'job_title')) {
                $table->string('job_title')->nullable();
            }
        });
    }
};
