<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Handle foreign key constraint for assigned_user_id
        try {
            \DB::statement("ALTER TABLE follow_ups DROP FOREIGN KEY follow_ups_assigned_user_id_foreign");
        } catch (\Exception $e) {
            // Foreign key doesn't exist
        }

        Schema::table('follow_ups', function (Blueprint $table) {
            // Drop soft deletes if exists
            if (Schema::hasColumn('follow_ups', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            // Drop unnecessary columns
            if (Schema::hasColumn('follow_ups', 'follow_up_id')) {
                $table->dropColumn('follow_up_id');
            }
            if (Schema::hasColumn('follow_ups', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
            if (Schema::hasColumn('follow_ups', 'notes')) {
                $table->dropColumn('notes');
            }

            // Rename assigned_user_id to user_id
            if (Schema::hasColumn('follow_ups', 'assigned_user_id')) {
                $table->renameColumn('assigned_user_id', 'user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        try {
            \DB::statement("ALTER TABLE follow_ups DROP FOREIGN KEY follow_ups_user_id_foreign");
        } catch (\Exception $e) {
            // Foreign key doesn't exist
        }

        Schema::table('follow_ups', function (Blueprint $table) {
            if (Schema::hasColumn('follow_ups', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            if (!Schema::hasColumn('follow_ups', 'follow_up_id')) {
                $table->string('follow_up_id')->unique()->after('id');
            }
            if (!Schema::hasColumn('follow_ups', 'completed_at')) {
                $table->datetime('completed_at')->nullable();
            }
            if (!Schema::hasColumn('follow_ups', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (Schema::hasColumn('follow_ups', 'user_id')) {
                $table->renameColumn('user_id', 'assigned_user_id');
                $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('set null');
            }

            if (!Schema::hasColumn('follow_ups', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }
};
