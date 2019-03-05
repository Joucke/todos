<?php

namespace App\Http\Controllers;

use App\User;
use App\Group;
use Illuminate\Http\Request;

class GroupUsersController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, User $user)
    {
        if (! $selfdestruction = auth()->user()->is($user)) {
            $this->authorize('update', $group);
        }

        $group->users()->detach($user);

        if ($selfdestruction) {
            return redirect(route('dashboard'))->with('status', __('groups.statuses.you_left'));
        }

        return redirect(route('groups.show', $group))->with('status', __('groups.statuses.member_removed'));
    }
}
