<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class ScheduledTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'task_id',
        'scheduled_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * The task this scheduled task belongs to
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Mark this scheduled task as completed
     */
    public function markCompleted(?Carbon $completedAt = null): static
    {
        $this->completed_at = $completedAt ?? now();
        $this->save();

        return $this;
    }

    /**
     * Check if this scheduled task is completed
     */
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Check if this scheduled task is overdue
     */
    public function isOverdue(): bool
    {
        return !$this->isCompleted() && now()->isAfter($this->scheduled_at);
    }

    /**
     * Get human readable status
     */
    public function status(): string
    {
        if ($this->isCompleted()) {
            return "Completed {$this->completed_at->diffForHumans()}";
        }

        if ($this->isOverdue()) {
            return "Overdue by {$this->scheduled_at->diffForHumans()}";
        }

        return "Due {$this->scheduled_at->diffForHumans()}";
    }
}
