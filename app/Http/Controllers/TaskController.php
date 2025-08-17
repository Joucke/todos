<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(TaskList $taskList)
    {
        $this->authorize('view', $taskList);

        // Load the group relationship
        $taskList->load('group');
        $group = $taskList->group;

        return view('tasks.create', compact('taskList', 'group'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TaskList $taskList)
    {
        $this->authorize('view', $taskList);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'interval' => 'required|integer|min:1',
            'days' => 'required|array|min:1',
            'days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'optional' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $validated['task_list_id'] = $taskList->id;
        $validated['optional'] = $request->has('optional');

        $task = Task::create($validated);

        return redirect()->route('groups.show', ['group' => $taskList->group, 'list' => $taskList->id])
                       ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
