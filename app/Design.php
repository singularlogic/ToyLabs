<?php

namespace App;

use BrianFaust\Commentable\HasComments;
use Conner\Likeable\LikeableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Design extends Model implements HasMedia
{
    use HasMediaTrait, HasComments, LikeableTrait;

    protected $fillable = ['title', 'description', 'is_public', 'parent_id', 'version', 'product_id'];
    protected $appends  = ['image', 'type', 'likeCount', 'commentCount', 'liked'];
    protected $touches  = ['product'];

    public function parent()
    {
        return $this->belontsTo(Design::class, 'parent_id');
    }

    public function revisions()
    {
        return $this->hasMany(Design::class, 'id', 'parent_id');
    }

    public function prototypes()
    {
        return $this->hasMany(Prototype::class);
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
        return 'design';
    }

    public function getLikedAttribute()
    {
        return $this->liked();
    }

    public function getCommentCountAttribute()
    {
        return $this->comments->count();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
