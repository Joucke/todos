<?php

namespace App\Http\Controllers;

use App\User;
use App\Group;
use Illuminate\Http\Request;

class GroupUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
        $this->authorize('update', $group);

        $user = User::findOrFail($request->input('user_id'));
        $group->users()->attach($user);

        return redirect(route('groups.show', $group));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group, User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, User $user)
    {
        //
    }
}
