<?php

namespace App\Http\Controllers;

use App\Group;
use App\TaskList;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class TaskListsController extends Controller
{
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

        return redirect(route('groups.show', $group))
            ->with('status', __('task_lists.statuses.created'));
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

        return redirect(route('groups.show', $taskList->group_id))
            ->with('status', __('task_lists.statuses.updated'));
    }

    public function sort(Request $request, Group $group)
    {
        $this->authorize('update', $group);

        $orders = $request->input('task_list_order');
        $lists = $group->task_lists;
        $lists->each(function (TaskList $list) use ($orders) {
            $list->update($orders[$list->id]);
        });

        return [
            'status' => 200,
            'items' => $group->task_lists()->with('tasks')->get(),
        ];
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

        return redirect(route('groups.show', $taskList->group_id))
            ->with('status', __('task_lists.statuses.deleted'));
    }
}
