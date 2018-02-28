<?php

namespace App;

use BrianFaust\Commentable\HasComments;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ARModel extends Model
{
    use HasMediaTrait, HasComments;

    protected $table    = 'ar_models';
    protected $fillable = ['title', 'description', 'downloads', 'parent_id', 'parent_type'];

    public function parent()
    {
        return $this->morphTo();
    }

    public function getModelAttribute()
    {
        // TODO: Return zipped AR model
    }

    public function questions()
    {
        return $this->hasMany(ARQuestion::class, 'ar_model_id');
    }

}
