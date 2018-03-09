<?php

namespace App\Models;

use App\User;
use Backpack\CRUD\CrudTrait;
use BrianFaust\Commentable\Comment;
use Illuminate\Database\Eloquent\Model;

class ReportedComment extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
     */

    protected $table = 'reported_comments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['comment_id', 'user_id'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
     */

    public function ignoreReport()
    {
        return '<a class="btn btn-xs btn-default" target="_blank" href="/admin/verify/comment/' . $this->id . '/ignore" data-button-type="ignore"><i class="fa fa-close"></i> Ignore</a>';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
     */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
     */

    public function getCreatorAttribute()
    {
        return $this->comment->creator->name;
    }

    /*
|--------------------------------------------------------------------------
| MUTATORS
|--------------------------------------------------------------------------
 */
}
