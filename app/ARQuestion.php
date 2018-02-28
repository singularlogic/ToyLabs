<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ARQuestion extends Model
{
    protected $table    = 'ar_questions';
    protected $fillable = ['text', 'armodel_id'];
}
