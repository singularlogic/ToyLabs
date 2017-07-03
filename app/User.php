<?php

namespace App;

use Cmgmyr\Messenger\Traits\Messagable;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, Notifiable, Messagable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that are appended on retrieval
     *
     * @var array
     */
    protected $appends = [
        'image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getImageAttribute()
    {
        return $this->avatar ?: Gravatar::get($this->email, ['size' => 80, 'fallback' => 'mm']);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'owner_id');
    }

    public function getRoleAttribute()
    {
        return $this->roles()->pluck('name')[0];
    }

    public function getHasOrganizationAttribute()
    {
        return $this->organizations()->count() > 0;
    }
}
