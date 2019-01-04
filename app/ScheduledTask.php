<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduledTask extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id',
        'scheduled_at',
    ];

    protected $dates = [
        'completed_at',
    ];

    public function task()
    {
    	return $this->belongsTo(Task::class);
    }

    public function complete()
    {
    	$this->completed_at = now();
    	$this->save();
    }

    public function scopeIncompleted($query)
    {
        return $query->whereNull('completed_at');
    }

    public function scopeCompleted($query)
    {
    	return $query->whereNotNull('completed_at');
    }
}
