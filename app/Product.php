<?php

namespace App;

use BrianFaust\Commentable\HasComments;
use Conner\Likeable\LikeableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Product extends Model implements HasMedia
{
    use HasComments, HasMediaTrait, LikeableTrait;

    protected $fillable = ['title', 'description', 'owner_id', 'owner_type', 'ages', 'status'];
    protected $appends  = ['image', 'type', 'likeCount', 'commentCount', 'liked'];

    public function owner()
    {
        return $this->morphTo();
    }

    public function getImageAttribute()
    {
        $covers = $this->getMedia('cover');
        if (count($covers) > 0) {
            return $covers[0]->getUrl();
        }

        return '/images/placeholder.jpg';
    }

    public function getTypeAttribute()
    {
        return 'product';
    }

    public function getCommentCountAttribute()
    {
        return $this->comments->count();
    }

    public function getLikedAttribute()
    {
        return $this->liked();
    }

    public function designs()
    {
        return $this->hasMany(Design::class);
    }

    public function prototypes()
    {
        return $this->hasMany(Prototype::class);
    }

    public function detailedComments()
    {
        return $this->comments()->with('creator');
    }
}
