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

    public function schedule()
    {
        return ScheduledTask::create([
            'task_id' => $this->id,
            'scheduled_at' => now()->addDays($this->interval),
        ]);
    }

    public function scheduled_tasks()
    {
        return $this->hasMany(ScheduledTask::class);
    }
}
