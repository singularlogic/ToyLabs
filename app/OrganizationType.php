<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class OrganizationType extends Model
{
    protected $fillable = ['name', 'role_id'];
    protected $appends  = ['slug'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getSlugAttribute()
    {
        return $this->role->name;
    }
}
