<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InvitationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Invitation $invitation): bool
    {
        // User can view if they're the group owner or the invited person
        return $invitation->group->isOwnedBy($user) || $invitation->email === $user->email;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Will be further restricted by group ownership
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Invitation $invitation): bool
    {
        return $invitation->group->isOwnedBy($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Invitation $invitation): bool
    {
        return $invitation->group->isOwnedBy($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Invitation $invitation): bool
    {
        return $invitation->group->isOwnedBy($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Invitation $invitation): bool
    {
        return $invitation->group->isOwnedBy($user);
    }

    /**
     * Determine whether the user can accept the invitation.
     */
    public function accept(User $user, Invitation $invitation): bool
    {
        return $invitation->email === $user->email && $invitation->isPending();
    }

    /**
     * Determine whether the user can decline the invitation.
     */
    public function decline(User $user, Invitation $invitation): bool
    {
        return $invitation->email === $user->email && $invitation->isPending();
    }
}
