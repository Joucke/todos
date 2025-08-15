<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Invitation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'email',
        'group_id',
        'accepted',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'accepted' => 'boolean',
        ];
    }

    /**
     * The group this invitation is for
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Accept this invitation
     */
    public function accept(): static
    {
        $this->accepted = true;
        $this->save();

        // Add user to group if they exist
        $user = User::where('email', $this->email)->first();
        if ($user && !$this->group->users->contains($user)) {
            $this->group->users()->attach($user);
        }

        return $this;
    }

    /**
     * Decline this invitation (mark as accepted = false)
     */
    public function decline(): static
    {
        $this->accepted = false;
        $this->save();

        return $this;
    }

    /**
     * Check if this invitation is pending
     */
    public function isPending(): bool
    {
        return $this->accepted === null;
    }

    /**
     * Check if this invitation was accepted
     */
    public function wasAccepted(): bool
    {
        return $this->accepted === true;
    }

    /**
     * Check if this invitation was declined
     */
    public function wasDeclined(): bool
    {
        return $this->accepted === false;
    }

    /**
     * Get the invited user if they exist
     */
    public function invitedUser(): ?User
    {
        return User::where('email', $this->email)->first();
    }

    /**
     * Scope for pending invitations
     */
    public function scopePending($query)
    {
        return $query->whereNull('accepted');
    }

    /**
     * Scope for accepted invitations
     */
    public function scopeAccepted($query)
    {
        return $query->where('accepted', true);
    }

    /**
     * Scope for declined invitations
     */
    public function scopeDeclined($query)
    {
        return $query->where('accepted', false);
    }
}
