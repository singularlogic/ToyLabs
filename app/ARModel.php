<?php

namespace App;

use BrianFaust\Commentable\HasComments;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class ARModel extends Model implements HasMedia
{
    use HasMediaTrait, HasComments;

    protected $table    = 'ar_models';
    protected $fillable = ['title', 'description', 'downloads', 'parent_id', 'parent_type'];
    protected $appends  = ['totalComments', 'averageRating', 'filename', 'thumbnail'];
    protected $with     = ['questions'];
    protected $hidden   = ['questions', 'comments', 'parent', 'parent_id', 'parent_type'];

    public function parent()
    {
        return $this->morphTo();
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

    public function questions()
    {
        return $this->hasMany(ARQuestion::class, 'ar_model_id');
    }

    public function getTotalCommentsAttribute()
    {
        return $this->commentCount();
    }

    public function getAverageRatingAttribute()
    {
        return $this->questions->sum('averageRating') / $this->questions->count();
    }

    public function getThumbnailAttribute()
    {
        if (count($this->parent->getMedia('images')) > 0) {
            return str_replace('file', 'thumb', $this->parent->image) . '.jpg';
        } else {
            return $this->parent->image;
        }
    }

    public function getFilenameAttribute()
    {
        return $this->id . '.zip';
    }
}
