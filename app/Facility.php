<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name', 'city_id', 'organization_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
