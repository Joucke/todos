<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskList;
use Illuminate\Http\Request;

class TasksController extends Controller
{
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

        $task = $taskList->tasks()->create($request->validate([
            'title' => 'required',
            'interval' => 'required',
            'days' => 'nullable',
            'days.mon' => 'boolean',
            'days.tue' => 'boolean',
            'days.wed' => 'boolean',
            'days.thu' => 'boolean',
            'days.fri' => 'boolean',
            'days.sat' => 'boolean',
            'days.sun' => 'boolean',
            'data' => 'nullable',
            'data.interval' => 'integer',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'optional' => 'nullable|boolean',
        ]));

        return [
            'status' => 200,
            'redirect' => route('task_lists.show', $taskList),
        ];
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

        $task->load('task_list');

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
            'days' => 'nullable',
            'days.mon' => 'boolean',
            'days.tue' => 'boolean',
            'days.wed' => 'boolean',
            'days.thu' => 'boolean',
            'days.fri' => 'boolean',
            'days.sat' => 'boolean',
            'days.sun' => 'boolean',
            'data' => 'nullable',
            'data.interval' => 'integer',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'optional' => 'nullable|boolean',
        ]));

        return [
            'status' => 200,
            'redirect' => route('task_lists.tasks.show', compact('taskList', 'task')),
        ];
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

        return redirect(route('task_lists.show', ['task_list' => $taskList]));
    }
}
