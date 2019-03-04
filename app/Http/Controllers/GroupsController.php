<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $group = $request->user()->owned_groups()->create(
            $request->validate([
                'title' => 'required',
            ])
        );

        $group->users()->attach($request->user());

        return redirect(route('groups.show', $group))
            ->with('status', __('groups.statuses.created'));
    }

    public function index(Request $request)
    {
        return view('groups.index')
            ->with('groups', auth()->user()->groups);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $this->authorize('view', $group);

        $group->load(['users', 'task_lists.tasks']);

        return view('groups.show')->withGroup($group);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $this->authorize('update', $group);

        return view('groups.edit')->withGroup($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $this->authorize('update', $group);

        $group->update(
            $request->validate([
                'title' => 'required',
            ])
        );

        return redirect(route('groups.show', $group))
            ->with('status', __('groups.statuses.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $this->authorize('update', $group);

        $group->users()->sync([]);
        $group->delete();

        return redirect(route('dashboard'))
            ->with('status', __('groups.statuses.deleted'));
    }
}
