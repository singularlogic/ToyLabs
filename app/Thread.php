<?php

namespace App;

use Cmgmyr\Messenger\Models\Models;
use Cmgmyr\Messenger\Models\Thread as BaseThread;

class Thread extends BaseThread
{
    protected $fillable = ['subject', 'target_id', 'target_type', 'type', 'locked', 'organization_id'];

    public function __construct(array $attributes = [])
    {
        $this->table = Models::table('threads');

        parent::__construct($attributes);
    }

    public function target()
    {
        return $this->morphTo();
    }
}
