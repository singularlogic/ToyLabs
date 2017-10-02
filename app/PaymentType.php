<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    protected $fillable = ['name'];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'payments_organizations');
    }
}
