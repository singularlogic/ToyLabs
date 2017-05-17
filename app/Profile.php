<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'about', 'address', 'country', 'telephone', 'facebook', 'twitter', 'linkedin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
