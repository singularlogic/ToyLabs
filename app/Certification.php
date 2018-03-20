<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use CrudTrait;

    protected $fillable = ['name', 'description'];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'certification_organization');
    }
}
