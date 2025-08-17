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
        Schema::create('group_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Sort order for displaying groups for a user (when they belong to multiple groups)
            $table->unsignedInteger('sort_order')->nullable();
            $table->timestamps();

            $table->unique(['group_id', 'user_id'], 'group_user_unique');
            $table->index(['user_id', 'sort_order'], 'group_user_user_sort_index');
            $table->index('group_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_user');
    }
};
