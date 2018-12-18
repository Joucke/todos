<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskList;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\TaskList  $taskList
     * @return \Illuminate\Http\Response
     */
    public function index(TaskList $taskList)
    {
        $this->authorize('view', $taskList);
        $taskList->load('tasks');
        return view('tasks.index', [
            'task_list' => $taskList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\TaskList  $taskList
     * @return \Illuminate\Http\Response
     */
    public function create(TaskList $taskList)
    {
        $this->authorize('view', $taskList);
        return view('tasks.create')->with([
            'task_list' => $taskList,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskList  $taskList
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TaskList $taskList)
    {
        $this->authorize('view', $taskList);

        $taskList->tasks()->create($request->validate([
            'title' => 'required',
            'interval' => 'required',
        ]));

        return redirect(route('task_lists.tasks.index', $taskList));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TaskList  $taskList
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(TaskList $taskList, Task $task)
    {
        $this->authorize('view', $taskList);

        return view('tasks.show')->with([
            'task' => $task,
            'task_list' => $taskList,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaskList  $taskList
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskList $taskList, Task $task)
    {
        $this->authorize('view', $taskList);

        return view('tasks.edit')->with([
            'task' => $task,
            'task_list' => $taskList,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskList  $taskList
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskList $taskList, Task $task)
    {
        $this->authorize('view', $taskList);

        $task->update($request->validate([
            'title' => 'required',
            'interval' => 'required',
        ]));
        return redirect(route('task_lists.tasks.index', ['task_list' => $taskList]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskList  $taskList
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskList $taskList, Task $task)
    {
        $this->authorize('view', $taskList);

        $task->delete();

        return redirect(route('task_lists.tasks.index', ['task_list' => $taskList]));
    }
}
