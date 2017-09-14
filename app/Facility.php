<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name', 'city_id', 'organization_id',
    ];
    protected $appends = [
        'state', 'country',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function getStateAttribute()
    {
        return $this->city->state()->first(['id', 'name']);
    }

    public function getCountryAttribute()
    {
        return $this->city->state->country()->first(['id', 'name']);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
