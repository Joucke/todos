<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Groups the user belongs to (many-to-many with sort order)
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('pivot_sort_order');
    }

    /**
     * Groups owned by this user
     */
    public function ownedGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    /**
     * Pending invitations for this user's email
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class, 'email', 'email')
            ->whereNull('accepted');
    }

    /**
     * Invitations sent by this user (through owned groups)
     */
    public function sentInvitations(): HasMany
    {
        return $this->hasManyThrough(
            Invitation::class,
            Group::class,
            'owner_id',
            'group_id'
        )->whereNull('accepted');
    }
}
