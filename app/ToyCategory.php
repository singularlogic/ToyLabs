<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToyCategory extends Model
{
    protected $fillable = ['title', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'toycategories_organizations', 'category_id', 'organization_id');
    }
}