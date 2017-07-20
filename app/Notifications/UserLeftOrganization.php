<?php

namespace App\Notifications;

use App\Organization;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class UserLeftOrganization extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Organization $organization)
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
            'type'     => 'organization.userleft',
            'sender'   => [
                'id'     => $this->user->id,
                'name'   => $this->user->name,
                'avatar' => $this->user->avatar,
            ],
            'receiver' => [
                'id'   => $this->organization->id,
                'name' => $this->organization->name,
            ],
            'message'  => $this->user->name . ' left ' . $this->organization->name . '.',
            'created'  => Carbon::now()->toIso8601String(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id'       => $this->id,
            'type'     => 'organization.userleft',
            'sender'   => [
                'id'     => $this->user->id,
                'name'   => $this->user->name,
                'avatar' => $this->user->avatar,
            ],
            'receiver' => [
                'id'   => $this->organization->id,
                'name' => $this->organization->name,
            ],
            'message'  => $this->user->name . ' left ' . $this->organization->name . '.',
            'created'  => Carbon::now()->toIso8601String(),
        ]);
    }
}
