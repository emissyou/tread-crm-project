<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            
            $table->string('title');
            $table->decimal('value', 15, 2)->nullable();
            $table->string('stage');
            $table->unsignedInteger('probability')->nullable();   // safer type
            $table->date('expected_close_date')->nullable();
            $table->date('closed_date')->nullable();
            $table->text('notes')->nullable();
            
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};