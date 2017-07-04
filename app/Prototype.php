<?php

namespace App;

use BrianFaust\Commentable\HasComments;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Prototype extends Model
{
    use HasMediaTrait, HasComments;

    protected $fillable = ['title', 'description', 'is_public', 'design_id'];
    protected $appends  = ['image', 'type'];

    public function design()
    {
        return $this->belongsTo(Design::class);
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
        return 'prototype';
    }
}
