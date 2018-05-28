<?php

namespace App;

use BrianFaust\Commentable\HasComments;
use Conner\Likeable\LikeableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Product extends Model implements HasMedia
{
    use HasComments, HasMediaTrait, LikeableTrait;

    protected $fillable = ['title', 'description', 'owner_id', 'owner_type', 'min_age', 'max_age', 'status', 'category_id', 'is_public'];
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

    public function getAgesAttribute()
    {
        $min = Age::where('value', $this->min_age)->first();
        $max = Age::where('value', $this->max_age)->first();

        $ages = '';
        if ($min) {
            $ages .= $min['name'];
            if ($max) {
                $ages .= ' - ' . $max['name'];
            } else {
                $ages .= '+';
            }

            return $ages;
        }

        return null;
    }

    public function getImagesAttribute()
    {
        $images = $this->getMedia('images');
        $result = [];
        foreach ($images as $image) {
            $result[] = $image->getUrl();
        }

        return $result;
    }

    public function getFilesAttribute()
    {
        $files  = $this->getMedia('files');
        $result = [];
        foreach ($files as $file) {
            $result[] = $file->getUrl();
        }

        return $result;
    }

    public function getArmodelsAttribute()
    {
        $models = new Collection();
        foreach ($this->designs as $design) {
            $models = $models->merge($design->armodels);
        }

        foreach ($this->prototypes as $prototype) {
            $models = $models->merge($prototype->armodels);
        }

        return $models;
    }
}
