<?php

namespace App;

use BrianFaust\Commentable\HasComments;
use Conner\Likeable\LikeableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Prototype extends Model implements HasMediaConversions
{
    use HasMediaTrait, HasComments, LikeableTrait;

    protected $fillable = ['title', 'description', 'is_public', 'design_id', 'product_id'];
    protected $appends  = ['image', 'type', 'likeCount', 'commentCount', 'liked'];
    protected $touches  = ['product'];

    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10)
            ->optimize();
    }

    public function design()
    {
        return $this->belongsTo(Design::class);
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
        return 'prototype';
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

    public function archive()
    {
        $this->is_archived = true;
        $this->save();
    }

    public function askFeedback()
    {
        foreach ($this->collaborations as $collaboration) {
            if ($collaboration->status === 'accepted') {
                // Ask myself to rate my collaborator
                CollaborationRating::create([
                    'collaboration_id'  => $collaboration->id,
                    'collaborator_id'   => $this->product->owner_id,
                    'collaborator_type' => $this->product->owner_type,
                    'organization_id'   => $collaboration->organization_id,
                ]);

                // Ask collaborator to rate my company (if I am a company)
                if ($this->product->owner_type === Organization::class) {
                    CollaborationRating::create([
                        'collaboration_id'  => $collaboration->id,
                        'collaborator_id'   => $collaboration->organization_id,
                        'collaborator_type' => Organization::class,
                        'organization_id'   => $this->product->owner_id,
                    ]);
                }

                $collaboration->archive();
            }
        }
    }
}
