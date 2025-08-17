<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the scheduled_tasks table for managing recurring task schedules.
     * This table tracks when tasks are scheduled to run and their completion status.
     */
    public function up(): void
    {
        Schema::create('scheduled_tasks', function (Blueprint $table) {
            $table->id();

            // Foreign key to tasks table with cascade delete
            // When a task is deleted, all its scheduled instances are also removed
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();

            // When this task instance is scheduled to run
            $table->timestamp('scheduled_at');

            // When this scheduled instance was completed (null = not yet completed)
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // Composite indexes for performance optimization
            $table->index(['task_id', 'scheduled_at'], 'scheduled_tasks_task_schedule_idx');
            $table->index(['scheduled_at', 'completed_at'], 'scheduled_tasks_due_status_idx');
            $table->index(['task_id', 'completed_at'], 'scheduled_tasks_task_completion_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_tasks');
    }
};
