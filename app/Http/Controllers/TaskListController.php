<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Group $group = null)
    {
        if ($group) {
            // Nested route: /groups/{group}/task-lists
            $this->authorize('view', $group);
            $group->load(['taskLists.tasks', 'members']);
            return view('task-lists.index', compact('group'));
        } else {
            // This shouldn't be used based on current routes
            return redirect()->route('groups.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Group $group = null)
    {
        if ($group) {
            // Nested route: /groups/{group}/task-lists/create
            $this->authorize('view', $group);
            return view('task-lists.create', compact('group'));
        } else {
            // Standalone route: /task-lists/create (not used based on routes)
            $user = auth()->user();
            $groups = $user->groups()->with('owner')->get();
            $ownedGroups = $user->ownedGroups()->get();
            $allGroups = $groups->merge($ownedGroups)->unique('id');

            return view('task-lists.create', compact('allGroups'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Group $group = null)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($group) {
            // Nested route: /groups/{group}/task-lists
            $this->authorize('view', $group);

            // Get the next sort order for this group
            $maxSortOrder = $group->taskLists()->max('sort_order') ?? 0;

            $taskList = $group->taskLists()->create([
                'name' => $validated['name'],
                'sort_order' => $maxSortOrder + 1,
            ]);

            return redirect()->route('groups.task-lists.index', $group)
                           ->with('success', 'Task list created successfully!');
        } else {
            // This shouldn't be used based on current routes
            return redirect()->route('groups.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskList $taskList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskList $taskList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskList $taskList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $taskList)
    {
        //
    }
}
