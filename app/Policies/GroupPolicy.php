<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can see their groups
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Group $group): bool
    {
        return $group->isAccessibleBy($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create groups
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Group $group): bool
    {
        return $group->isOwnedBy($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Group $group): bool
    {
        return $group->isOwnedBy($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Group $group): bool
    {
        return $group->isOwnedBy($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Group $group): bool
    {
        return $group->isOwnedBy($user);
    }

    /**
     * Determine whether the user can manage members of the group.
     */
    public function manageMembers(User $user, Group $group): bool
    {
        return $group->isOwnedBy($user);
    }

    /**
     * Determine whether the user can invite others to the group.
     */
    public function invite(User $user, Group $group): bool
    {
        return $group->isOwnedBy($user);
    }

    /**
     * Determine whether the user can leave the group.
     */
    public function leave(User $user, Group $group): bool
    {
        // Owner cannot leave their own group, members can leave
        return $group->members->contains($user) && !$group->isOwnedBy($user);
    }
}
