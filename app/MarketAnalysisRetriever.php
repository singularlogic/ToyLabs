<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketAnalysisRetriever extends Model
{
    protected $fillable = ['name', 'anlzer_retriever_id', 'organization_id', 'anlzer_data'];
}
