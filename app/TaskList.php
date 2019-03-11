<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'group_id',
        'sort_order',
    ];

    public function group()
    {
    	return $this->belongsTo(Group::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function completed_tasks()
    {
        return $this->hasManyThrough(ScheduledTask::class, Task::class)
            ->completed()->latest('completed_at');
    }

    public function getSortFieldAttribute()
    {
        return $this->sort_order;
    }
}
