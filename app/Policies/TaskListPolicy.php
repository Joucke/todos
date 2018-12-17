<?php

namespace App\Policies;

use App\User;
use App\TaskList;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the task list.
     *
     * @param  \App\User  $user
     * @param  \App\TaskList  $taskList
     * @return mixed
     */
    public function view(User $user, TaskList $taskList)
    {
        return $taskList->group->users->pluck('id')->contains($user->id);
    }

    /**
     * Determine whether the user can create task lists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the task list.
     *
     * @param  \App\User  $user
     * @param  \App\TaskList  $taskList
     * @return mixed
     */
    public function update(User $user, TaskList $taskList)
    {
        return $taskList->group->owner->is($user);
    }

    /**
     * Determine whether the user can delete the task list.
     *
     * @param  \App\User  $user
     * @param  \App\TaskList  $taskList
     * @return mixed
     */
    public function delete(User $user, TaskList $taskList)
    {
        return $taskList->group->owner->is($user);
    }

    /**
     * Determine whether the user can restore the task list.
     *
     * @param  \App\User  $user
     * @param  \App\TaskList  $taskList
     * @return mixed
     */
    public function restore(User $user, TaskList $taskList)
    {
        return $taskList->group->owner->is($user);
    }

    /**
     * Determine whether the user can permanently delete the task list.
     *
     * @param  \App\User  $user
     * @param  \App\TaskList  $taskList
     * @return mixed
     */
    public function forceDelete(User $user, TaskList $taskList)
    {
        return $taskList->group->owner->is($user);
    }
}
