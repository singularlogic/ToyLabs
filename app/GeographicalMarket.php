<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeographicalMarket extends Model
{
    protected $fillable = ['name'];

    public function Organizations()
    {
        return $this->belongsToMany(Organization::class, 'geographical_market_organization');
    }
}
