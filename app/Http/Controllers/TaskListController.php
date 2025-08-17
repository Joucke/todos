<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
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

            return redirect()->route('groups.show', $group)
                           ->with('success', 'Task list created successfully!');
        } else {
            // This shouldn't be used based on current routes
            return redirect()->route('groups.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskList $taskList)
    {
        $this->authorize('update', $taskList);

        // Load the group relationship
        $taskList->load('group', 'tasks');
        $group = $taskList->group;

        return view('task-lists.edit', compact('taskList', 'group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskList $taskList)
    {
        $this->authorize('update', $taskList);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $taskList->update($validated);

        return redirect()->route('groups.show', $taskList->group)
                       ->with('success', 'Task list updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $taskList)
    {
        $this->authorize('delete', $taskList);

        $group = $taskList->group;
        $taskListName = $taskList->name;

        $taskList->delete();

        return redirect()->route('groups.show', $group)
                       ->with('success', "Task list '{$taskListName}' has been removed successfully!");
    }
}
