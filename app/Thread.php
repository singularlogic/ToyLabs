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

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public static function findID(string $type, $target, int $org_id)
    {
        $t = Thread::where('organization_id', $org_id)
            ->where('target_id', $target->id)
            ->where('target_type', get_class($target))
            ->where('type', $type)
            ->first();

        if ($t) {
            return $t->id;
        }

        return null;
    }
}
