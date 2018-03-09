<?php

namespace App\Models;

use App\Certification;
use App\Organization;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class VerifyCertification extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
     */

    protected $table = 'certification_organization';
    // protected $primaryKey = 'id';
    public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['is_verified'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
     */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
     */

    public function certification()
    {
        return $this->belongsTo(Certification::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
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

    /*
|--------------------------------------------------------------------------
| MUTATORS
|--------------------------------------------------------------------------
 */
}
