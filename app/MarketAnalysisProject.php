<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketAnalysisProject extends Model
{
    protected $fillable = ['name', 'anlzer_project_id', 'anlzer_data', 'is_public', 'product_id'];

}
