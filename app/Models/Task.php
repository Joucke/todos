<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'task_list_id',
        'interval',
        'days',
        'data',
        'optional',
        'starts_at',
        'ends_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'days' => 'array',
            'data' => 'array',
            'optional' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'interval' => 'integer',
        ];
    }

    /**
     * The task list this task belongs to
     */
    public function taskList(): BelongsTo
    {
        return $this->belongsTo(TaskList::class);
    }

    /**
     * Scheduled instances of this task
     */
    public function scheduledTasks(): HasMany
    {
        return $this->hasMany(ScheduledTask::class);
    }

    /**
     * Completed scheduled tasks
     */
    public function completedScheduledTasks(): HasMany
    {
        return $this->hasMany(ScheduledTask::class)->whereNotNull('completed_at');
    }

    /**
     * Pending scheduled tasks
     */
    public function pendingScheduledTasks(): HasMany
    {
        return $this->hasMany(ScheduledTask::class)->whereNull('completed_at');
    }

    /**
     * Get the URL for completing this task
     */
    protected function completionUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => route('tasks.scheduled-tasks.store', $this)
        );
    }

    /**
     * Parse the interval into human readable format
     */
    public function parseInterval(): string
    {
        return match ($this->interval) {
            1 => 'Daily',
            7 => 'Weekly',
            14 => 'Bi-weekly',
            30 => 'Monthly',
            default => "{$this->interval} days"
        };
    }

    /**
     * Check if task is currently active (within date range if specified)
     */
    public function isActive(): bool
    {
        $now = now();

        if ($this->starts_at && $now->isBefore($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->isAfter($this->ends_at)) {
            return false;
        }

        return true;
    }

    /**
     * Get the next due date for this task
     */
    public function nextDueDate(): ?Carbon
    {
        if (!$this->isActive()) {
            return null;
        }

        $lastCompleted = $this->completedScheduledTasks()
            ->latest('completed_at')
            ->first();

        $baseDate = $lastCompleted
            ? $lastCompleted->completed_at
            : ($this->starts_at ?? now());

        return $baseDate->copy()->addDays($this->interval);
    }

    /**
     * Check if this task is overdue
     */
    public function isOverdue(): bool
    {
        $nextDue = $this->nextDueDate();
        return $nextDue && now()->isAfter($nextDue);
    }

    /**
     * Get human readable due status
     */
    public function dueStatus(): string
    {
        $nextDue = $this->nextDueDate();

        if (!$nextDue) {
            return $this->isActive() ? 'No due date' : 'Inactive';
        }

        return $nextDue->diffForHumans();
    }
}
