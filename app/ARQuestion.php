<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ARQuestion extends Model
{
    protected $table    = 'ar_questions';
    protected $fillable = ['text', 'armodel_id'];
    protected $appends  = ['averageRating'];

    public function answers()
    {
        return $this->hasMany(ARQuestionAnswer::class, 'ar_question_id');
    }

    public function getAverageRatingAttribute()
    {
        if ($this->answers->count() === 0) {
            return 0;
        }

        return $this->answers->sum('value') / $this->answers->count();
    }
}
