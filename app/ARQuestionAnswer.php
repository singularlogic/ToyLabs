<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ARQuestionAnswer extends Model
{
    protected $table    = 'ar_question_answers';
    protected $fillable = ['ar_question_id', 'user_id', 'value'];

    protected function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function question()
    {
        return $this->belongsTo(ARQuestion::class, 'ar_question_id');
    }
}
