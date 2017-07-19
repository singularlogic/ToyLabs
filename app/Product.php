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

    protected $fillable = ['title', 'description', 'owner_id', 'owner_type', 'ages', 'status', 'category_id'];
    protected $appends  = ['image', 'type', 'likeCount', 'commentCount', 'liked', 'paragraphedDescription'];

    public function owner()
    {
        return $this->morphTo();
    }

    public function getImageAttribute()
    {
        $images = $this->getMedia('images');
        if (count($images) > 0) {
            return $images[0]->getUrl();
        }

        return '/images/placeholder.jpg';
    }

    public function getParagraphedDescriptionAttribute()
    {
        $paragraphs = explode("\n", $this->description);

        return '<p>' . implode('</p><p>', $paragraphs) . '</p>';
    }

    public function category()
    {
        return $this->belongsTo(ToyCategory::class, 'category_id');
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
