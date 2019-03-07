<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class CompletedTasksController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Task $task)
    {
        try {
            $scheduled = $task->scheduled_tasks()->incompleted()->firstOrFail();
        }
        catch (\Exception $e) {
            $scheduled = $task->schedule(0);
        }
        $scheduled->complete();
        $task->schedule();

        return redirect(route('task_lists.show', ['task_list' => $task->task_list_id]));
    }
}
