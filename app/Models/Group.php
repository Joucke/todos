<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'owner_id',
    ];

    /**
     * The owner of this group
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Users who are members of this group (many-to-many with sort order)
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('pivot_sort_order');
    }

    /**
     * Task lists belonging to this group
     */
    public function taskLists(): HasMany
    {
        return $this->hasMany(TaskList::class)->orderBy('sort_order');
    }

    /**
     * All tasks in this group (through task lists)
     */
    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, TaskList::class);
    }

    /**
     * Invitations for this group
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Pending invitations for this group
     */
    public function pendingInvitations(): HasMany
    {
        return $this->hasMany(Invitation::class)->whereNull('accepted');
    }

    /**
     * Check if a user can access this group (owner or member)
     */
    public function isAccessibleBy(User $user): bool
    {
        return $this->owner->is($user) || $this->members->contains($user);
    }

    /**
     * Check if a user is the owner of this group
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->owner->is($user);
    }
}
