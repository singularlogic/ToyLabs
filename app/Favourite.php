<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $fillable = [
        'user_id', 'favouritable_id', 'favouritable_type',
    ];

    public function favouritable()
    {
        return $this->morphTo();
    }
}
