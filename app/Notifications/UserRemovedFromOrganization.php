<?php

namespace App\Notifications;

use App\Organization;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class UserRemovedFromOrganization extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Organization $organization, User $user)
    {
        $this->user         = $user;
        $this->organization = $organization;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id'       => $this->id,
            'type'     => 'organization.userremoved',
            'sender'   => [
                'id'     => $this->organization->id,
                'name'   => $this->organization->name,
                'avatar' => null,
            ],
            'receiver' => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],
            'message'  => 'You have been removed from ' . $this->organization->name . '.',
            'created'  => Carbon::now()->toIso8601String(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id'       => $this->id,
            'type'     => 'organization.userremoved',
            'sender'   => [
                'id'     => $this->organization->id,
                'name'   => $this->organization->name,
                'avatar' => null,
            ],
            'receiver' => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],
            'message'  => 'You have been removed from ' . $this->organization->name . '.',
            'created'  => Carbon::now()->toIso8601String(),
        ]);
    }
}
