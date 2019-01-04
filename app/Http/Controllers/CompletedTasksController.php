<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class CompletedTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     //
    // }

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

        return redirect(route('task_lists.tasks.index', ['task_list' => $task->task_list_id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    // public function show(Task $task)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    // public function edit(Task $task)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Task $task)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Task $task)
    // {
    //     //
    // }
}
