<?php

namespace App\Listeners;

use App\Notifications\UserDeclinedAccess;
use App\Notifications\UserJoinedOrganization;
use App\Notifications\UserWantsToJoinOrganization;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class MembershipEventSubscriber implements ShouldQueue
{
    public function sent($sender, $recipient)
    {
        $owner = User::find($recipient->owner_id);
        $owner->notify(new UserWantsToJoinOrganization($sender, $recipient));
    }

    public function accepted($sender, $recipient)
    {
        $sender->users()->save($recipient);
        $recipient->notify(new UserJoinedOrganization($sender, $recipient));
    }

    public function denied($sender, $recipient)
    {
        $recipient->notify(new UserDeclinedAccess($sender, $recipient));
    }

    public function blocked($sender, $recipient)
    {
    }

    public function unblocked($sender, $recipient)
    {
    }

    public function cancelled($sender, $recipient)
    {
    }

    public function subscribe($events)
    {
        $events->listen('friendships.sent', 'App\Listeners\MembershipEventSubscriber@sent');
        $events->listen('friendships.accepted', 'App\Listeners\MembershipEventSubscriber@accepted');
        $events->listen('friendships.denied', 'App\Listeners\MembershipEventSubscriber@denied');
        $events->listen('friendships.blocked', 'App\Listeners\MembershipEventSubscriber@blocked');
        $events->listen('friendships.unblocked', 'App\Listeners\MembershipEventSubscriber@unblocked');
        $events->listen('friendships.cancelled', 'App\Listeners\MembershipEventSubscriber@cancelled');
    }
}
