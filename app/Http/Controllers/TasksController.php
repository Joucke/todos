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
     * @param  \App\TaskList  $task_list
     * @return \Illuminate\Http\Response
     */
    public function create(TaskList $task_list)
    {
        $this->authorize('view', $task_list);
        return view('tasks.create')->with(compact('task_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskList  $task_list
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TaskList $task_list)
    {
        $this->authorize('view', $task_list);

        $task = $task_list->tasks()->create($request->validate([
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
            'redirect' => route('task_lists.show', $task_list),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TaskList  $task_list
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(TaskList $task_list, Task $task)
    {
        $this->authorize('view', $task_list);

        $task->load('task_list');

        return view('tasks.show')->with(compact('task', 'task_list'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaskList  $task_list
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskList $task_list, Task $task)
    {
        $this->authorize('view', $task_list);

        return view('tasks.edit')->with(compact('task', 'task_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskList  $task_list
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskList $task_list, Task $task)
    {
        $this->authorize('view', $task_list);

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
            'redirect' => route('task_lists.tasks.show', compact('task_list', 'task')),
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskList  $task_list
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskList $task_list, Task $task)
    {
        $this->authorize('view', $task_list);

        $task->delete();

        return redirect(route('task_lists.show', $task_list));
    }
}
