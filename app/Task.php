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
}
