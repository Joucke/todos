<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    ];

    public function task_list()
    {
    	return $this->belongsTo(TaskList::class);
    }

    public function schedule(int $interval = null)
    {
        $interval = $interval ?? $this->interval;
        return ScheduledTask::create([
            'task_id' => $this->id,
            'scheduled_at' => now()->addDays($interval),
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
}
