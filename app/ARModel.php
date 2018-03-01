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
    protected $appends  = ['totalComments', 'averageRating'];
    protected $with     = ['questions'];

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

    public function getModelFilesAttribute()
    {
        // TODO: Return zipped AR model
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
}
