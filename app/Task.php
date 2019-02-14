<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
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

    protected $casts = [
        'days' => 'array',
        'data' => 'array',
        'optional' => 'boolean',
    ];

    protected $dates = [
        'starts_at',
        'ends_at',
    ];

    protected $appends = [
        'url',
    ];

    protected function getUrlAttribute()
    {
        return route('tasks.completed_tasks.store', $this);
    }

    public function task_list()
    {
    	return $this->belongsTo(TaskList::class);
    }

    public function schedule(int $interval = null)
    {
        $interval = $interval ?? $this->interval;
        return ScheduledTask::create([
            'task_id' => $this->id,
            'scheduled_at' => $this->getNextDate($interval),
        ]);
    }

    public function scheduled_tasks()
    {
        return $this->hasMany(ScheduledTask::class);
    }

    public function incompleted_scheduled_tasks()
    {
        return $this->hasMany(ScheduledTask::class)->whereNull('completed_at');
    }

    protected function getNextDate(int $interval)
    {
        $nextDate = now()->addDays($interval);
        if (is_array($this->days)) {
            $nextDate = $this->getNextDayOfWeek();
        }
        if ($this->ends_at && $nextDate->gt($this->ends_at)) {
            $this->incrementPeriodYear();
            return $this->starts_at;
        }
        return $nextDate;
    }

    protected function incrementPeriodYear()
    {
        $attributes = [
            'starts_at' => $this->starts_at->addYear(),
            'ends_at' => $this->ends_at->addYear(),
        ];
        $this->update($attributes);
    }

    protected function getNextDayOfWeek()
    {
        return collect($this->days)
            ->filter()
            ->map(function ($day, $key) {
                return new Carbon('next '.$key);
            })
            ->sort()
            ->first();
    }
}
