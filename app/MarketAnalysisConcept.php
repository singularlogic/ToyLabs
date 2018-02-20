<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketAnalysisConcept extends Model
{
    protected $fillable = ['name', 'anlzer_concept_id', 'anlzer_project_id', 'anlzer_data'];
}
