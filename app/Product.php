<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'description', 'owner_id', 'owner_type',
    ];

    public function owner()
    {
        return $this->morphTo();
    }
}
