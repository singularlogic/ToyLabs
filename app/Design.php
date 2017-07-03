<?php

namespace App;

use BrianFaust\Commentable\HasComments;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Design extends Model
{
    use HasMediaTrait, HasComments;

    protected $fillable = [
        'title', 'description', 'is_public', 'parent_id', 'version',
    ];

    public function parent()
    {
        return $this->belontsTo(Design::class, 'parent_id');
    }

    public function revisions()
    {
        return $this->hasMany(Design::class, 'id', 'parent_id');
    }

    public function prototype()
    {
        return $this->hasOne(Prototype::class);
    }
}
