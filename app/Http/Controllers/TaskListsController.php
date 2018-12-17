<?php

namespace App\Http\Controllers;

use App\Group;
use App\TaskList;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class TaskListsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = auth()->user()->groups->pluck('id');
        $task_lists = TaskList::whereIn('group_id', $groups)->get();
        return view('task_lists.index')->with(compact('task_lists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('task_lists.create')->with([
            'groups' => auth()->user()->groups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $group = Group::findOrFail($request->input('group_id'));
        abort_unless($group->users->contains('id', auth()->id()), 403);

        TaskList::create($request->validate([
            'group_id' => 'required',
            'title' => 'required',
        ]));

        return redirect(route('task_lists.index'));
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
            'group_id' => 'required',
            'title' => 'required',
        ]));

        return redirect(route('task_lists.index'));
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
        
        return redirect(route('task_lists.index'));
    }
}
