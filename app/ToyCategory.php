<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToyCategory extends Model
{
    protected $fillable = ['title', 'description'];

    protected function products()
    {
        return $this->hasMany(Product::class);
    }
}
