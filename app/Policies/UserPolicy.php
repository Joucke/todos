<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the current user can view the user.
     *
     * @param  \App\User  $currentUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $currentUser, User $user)
    {
        $sharedGroups = $currentUser->groups->intersect($user->groups);
        $ownedSharedGroups = $currentUser->owned_groups->intersect($user->groups);
        $sharedOwnedGroups = $currentUser->groups->intersect($user->owned_groups);

        return $currentUser->is($user)
            || $sharedGroups->count() > 0
            || $ownedSharedGroups->count() > 0
            || $sharedOwnedGroups->count() > 0;
    }

    /**
     * Determine whether the current user can update the user.
     *
     * @param  \App\User  $currentUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $currentUser, User $user)
    {
        return $currentUser->is($user);
    }

    /**
     * Determine whether the current user can delete the user.
     *
     * @param  \App\User  $currentUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $currentUser, User $user)
    {
        //
    }

    /**
     * Determine whether the current user can restore the user.
     *
     * @param  \App\User  $currentUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function restore(User $currentUser, User $user)
    {
        //
    }

    /**
     * Determine whether the current user can permanently delete the user.
     *
     * @param  \App\User  $currentUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function forceDelete(User $currentUser, User $user)
    {
        //
    }
}
