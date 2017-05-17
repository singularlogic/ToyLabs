<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['name', 'description', 'owner_id', 'organization_type_id'];

    public function owner()
    {
        return $this->hasOne(User::class, 'owner_id');
    }

    public function type()
    {
        return $this->hasOne(OrganizationType::class);
    }
}
