<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_list_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('interval'); // Interval in days for recurring tasks
            $table->boolean('optional')->default(false); // Whether task completion is optional
            $table->timestamp('starts_at')->nullable(); // Seasonal start date
            $table->timestamp('ends_at')->nullable(); // Seasonal end date
            $table->json('days')->nullable(); // Specific weekdays when task should run
            $table->json('data')->nullable(); // Additional interval configuration (legacy format)
            $table->timestamps();

            $table->index('task_list_id');
            $table->index(['starts_at', 'ends_at']); // For seasonal task queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
