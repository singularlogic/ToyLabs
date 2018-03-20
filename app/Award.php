<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use CrudTrait;

    protected $fillable = ['name', 'url'];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'award_organization')->withPivot('awarded_at');
    }
}
