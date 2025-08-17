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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto-increment
            $table->string('email');
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete(); // explicit table reference
            $table->foreignId('inviter_id')->constrained('users')->cascadeOnDelete(); // who sent the invitation
            $table->boolean('accepted')->nullable(); // can be null until acted upon
            $table->timestamps();

            // Indexes for performance
            $table->index(['email', 'accepted']); // lookup invitations by email/status
            $table->index('group_id'); // lookup invitations by group
            $table->index('inviter_id'); // lookup invitations by inviter
            $table->unique(['email', 'group_id']); // prevent duplicate invitations
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
