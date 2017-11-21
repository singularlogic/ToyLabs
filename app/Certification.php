<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = ['name'];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'certification_organization');
    }
}
