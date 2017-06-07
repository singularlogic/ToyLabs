<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class OrganizationType extends Model
{
    protected $fillable = ['name', 'role_id'];

    public function role()
    {
        return $this->hasOne(Role::class);
    }
}
