<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = ['name', 'url'];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'award_organization')->withPivot('awarded_at');
    }
}
