<?php

namespace App\Http\Controllers;

use App\Group;
use App\TaskList;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class TaskListsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Group $group)
    {
        $this->authorize('view', $group);

        return view('task_lists.create')->withGroup($group);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Group $group, Request $request)
    {
        $this->authorize('view', $group);

        $group->task_lists()->create($request->validate([
            'title' => 'required',
        ]));

        return redirect(route('groups.show', $group));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TaskList  $taskList
     * @return \Illuminate\Http\Response
     */
    public function show(TaskList $taskList)
    {
        $this->authorize('view', $taskList);

        return view('task_lists.show', ['task_list' => $taskList]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaskList  $taskList
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskList $taskList)
    {
        $this->authorize('update', $taskList);

        return view('task_lists.edit', [
            'task_list' => $taskList,
            'groups' => auth()->user()->groups,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskList  $taskList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskList $taskList)
    {
        $this->authorize('update', $taskList);

        $taskList->update($request->validate([
            'title' => 'required',
        ]));

        return redirect(route('groups.show', $taskList->group_id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskList  $taskList
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskList $taskList)
    {
        $this->authorize('delete', $taskList);

        $taskList->delete();

        return redirect(route('groups.show', $taskList->group_id));
    }
}
