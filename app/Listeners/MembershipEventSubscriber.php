<?php

namespace App\Listeners;

use App\Notifications\UserWantsToJoinOrganization;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class MembershipEventSubscriber implements ShouldQueue
{
    public function sent($user, $organization)
    {
        $recipient = User::find($organization->owner_id);
        $recipient->notify(new UserWantsToJoinOrganization($user, $organization));
    }

    public function accepted($user, $organization)
    {
    }

    public function denied($user, $organization)
    {
    }

    public function blocked($user, $organization)
    {
    }

    public function unblocked($user, $organization)
    {
    }

    public function cancelled($user, $organization)
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
