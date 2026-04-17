<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Handle foreign key constraint for created_by/user_id
        try {
            \DB::statement("ALTER TABLE activities DROP FOREIGN KEY activities_created_by_foreign");
        } catch (\Exception $e) {
            // Foreign key doesn't exist
        }

        Schema::table('activities', function (Blueprint $table) {
            // Drop soft deletes if exists
            if (Schema::hasColumn('activities', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('activities', function (Blueprint $table) {
            // Drop unnecessary columns
            if (Schema::hasColumn('activities', 'activity_id')) {
                $table->dropColumn('activity_id');
            }
            if (Schema::hasColumn('activities', 'metadata')) {
                $table->dropColumn('metadata');
            }

            // Rename columns
            if (Schema::hasColumn('activities', 'date')) {
                $table->renameColumn('date', 'activity_date');
            }
            if (Schema::hasColumn('activities', 'created_by')) {
                $table->renameColumn('created_by', 'user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        try {
            \DB::statement("ALTER TABLE activities DROP FOREIGN KEY activities_user_id_foreign");
        } catch (\Exception $e) {
            // Foreign key doesn't exist
        }

        Schema::table('activities', function (Blueprint $table) {
            if (Schema::hasColumn('activities', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'activity_id')) {
                $table->string('activity_id')->unique()->after('id');
            }
            if (!Schema::hasColumn('activities', 'metadata')) {
                $table->json('metadata')->nullable();
            }

            if (Schema::hasColumn('activities', 'activity_date')) {
                $table->renameColumn('activity_date', 'date');
            }
            if (Schema::hasColumn('activities', 'user_id')) {
                $table->renameColumn('user_id', 'created_by');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            }

            if (!Schema::hasColumn('activities', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }
};
