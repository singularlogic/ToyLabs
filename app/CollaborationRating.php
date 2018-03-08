<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollaborationRating extends Model
{
    protected $fillable = [
        'collaboration_id', 'collaborator_id', 'collaborator_type', 'organization_id', 'rating_1', 'rating_2', 'rating_3',
    ];

    public function collaboration()
    {
        return $this->belongsTo(Collaboration::class);
    }

    public function collaborator()
    {
        return $this->morphTo();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
