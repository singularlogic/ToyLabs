<?php

namespace App;

use BrianFaust\Commentable\HasComments;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasComments;

    protected $fillable = ['title', 'description', 'owner_id', 'owner_type'];
    protected $appends  = ['image', 'type'];

    public function owner()
    {
        return $this->morphTo();
    }

    public function getImageAttribute()
    {
        $covers = $this->getMedia('cover');
        if ($covers) {
            return $covers[0]->getUrl();
        }

        return '/images/placeholder.jpg';
    }

    public function getTypeAttribute()
    {
        return 'product';
    }
}
