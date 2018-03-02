<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ARQuestion extends Model
{
    protected $table    = 'ar_questions';
    protected $fillable = ['text', 'armodel_id'];
    protected $appends  = ['averageRating', 'detailedRating'];
    protected $casts    = [
        'detailedRating' => 'array',
    ];

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

    public function getDetailedRatingAttribute()
    {
        $result = DB::table('ar_question_answers')
            ->select(DB::raw('value, count(id) as total'))
            ->where('ar_question_id', $this->id)
            ->groupBy('value')
            ->orderBy('value', 'ASC')
            ->get();

        $ratings = [0, 0, 0, 0, 0];
        foreach ($result as $r) {
            $ratings[$r->value - 1] = $r->total;
        }

        return $ratings;
    }
}
