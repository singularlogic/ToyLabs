<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketAnalysisAnalysis extends Model
{
    protected $fillable = ['name', 'type', 'anlzer_analysis_id', 'anlzer_project_id', 'product_id', 'anlzer_data', 'data', 'settings', 'is_public'];

}
