<?php

namespace App;

use BrianFaust\Commentable\HasComments;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Prototype extends Model
{
    use HasMediaTrait, HasComments;

    protected $fillable = [
        'title', 'description', 'is_public', 'design_id',
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}
