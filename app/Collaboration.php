<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collaboration extends Model
{
    protected $fillable = ['status', 'organization_id', 'collaboratable_id', 'collaboratable_type'];

    public function accept()
    {
        $this->status = 'accepted';
        $this->save();
    }

    public function reject()
    {
        $this->status = 'rejected';
        $this->save();
    }

    public function archive()
    {
        $this->status = 'archived';
        $this->save();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function collaboratable()
    {
        return $this->morphTo();
    }
}
