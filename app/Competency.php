<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    protected $fillable = ['name'];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'competencies_organizations');
    }
}
