<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function owned_groups()
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'email', 'email')
            ->whereNull('accepted');
    }

    public function invites()
    {
        return $this->hasManyThrough(Invitation::class, Group::class, 'owner_id')
            ->whereNull('accepted');
    }
}
