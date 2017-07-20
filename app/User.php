<?php

namespace App;

use App\Organization;
use Cmgmyr\Messenger\Traits\Messagable;
use Creativeorange\Gravatar\Facades\Gravatar;
use Hootlex\Friendships\Status;
use Hootlex\Friendships\Traits\Friendable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, Notifiable, Messagable, Friendable;

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
        return $this->belongsToMany(Organization::class, 'organization_user');
    }

    public function myOrganizations()
    {
        return $this->hasMany(Organization::class, 'owner_id');
    }

    public function getRoleAttribute()
    {
        return $this->roles()->pluck('name')[0];
    }

    public function getMembershipsAttribute()
    {
        $friendships = $this->findFriendships(Status::ACCEPTED)->get();
        $recipients  = $friendships->pluck('recipient_id')->all();
        $senders     = $friendships->pluck('sender_id')->all();

        return Organization::whereIn('id', array_merge($recipients, $senders))->get();
    }

    public function getPendingMembershipsAttribute()
    {
        $friendships = $this->findFriendships(Status::PENDING)->get();
        $recipients  = $friendships->pluck('recipient_id')->all();
        $senders     = $friendships->pluck('sender_id')->all();

        return Organization::whereIn('id', array_merge($recipients, $senders))->get();
    }

    public function getOrganizationAttribute()
    {
        if ($this->myOrganizations()->count()) {
            return $this->myOrganizations[0]->id;
        }

        return $this->organizations()->count() ? $this->organizations[0]->id : -1;
    }

    public function getHasOrganizationAttribute()
    {
        return $this->myOrganizations()->count() > 0;
    }
}
