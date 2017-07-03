<?php

namespace App;

use BrianFaust\Commentable\HasComments;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasComments;

    protected $fillable = [
        'title', 'description', 'owner_id', 'owner_type',
    ];

    public function owner()
    {
        return $this->morphTo();
    }
}
