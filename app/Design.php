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
        return $this->belongsTo(Design::class, 'parent_id');
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
        $images = $this->getMedia('images');
        if (count($images) > 0) {
            return $images[0]->getUrl();
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

    public function negotiations()
    {
        return $this->morphMany(Thread::class, 'target')->where('type', 'negotiation');
    }

    public function feedback()
    {
        return $this->morphMany(Thread::class, 'target')->where('type', 'feedback');
    }

    public function collaborations()
    {
        return $this->morphMany(Collaboration::class, 'collaboratable');
    }

    public function armodels()
    {
        return $this->morphMany(ARModel::class, 'parent');
    }
}
