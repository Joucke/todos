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
    ];

    public function group()
    {
    	return $this->belongsTo(Group::class);
    }
}
