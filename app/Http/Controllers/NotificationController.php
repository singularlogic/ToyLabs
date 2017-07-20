<?php

namespace App\Http\Controllers;

use App\Events\NotificationRead;
use App\Events\NotificationReadAll;
use App\Organization;
use App\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('last', 'dismiss');
    }

    /**
     * Get User's feed page
     *
     * @param  \Illuminate\Http\Request $request
     * @return view to the feeds page
     */
    public function feed(Request $request, $tab = 'notifications')
    {
        return view('feed', []);
    }

    /**
     * Get user's notifications.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = $user->unreadNotifications();
        $total = $user->unreadNotifications->count();

        $notifications = $query->get()->each(function ($n) {
            $n->created = $n->created_at->toIso8601String();
        });

        return compact('notifications', 'total');
    }

    /**
     * Mark user's notification as read.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()
            ->unreadNotifications()
            ->where('id', $id)
            ->first();

        if (is_null($notification)) {
            return response()->json('Notification not found.', 404);
        }

        $notification->markAsRead();
        event(new NotificationRead($request->user()->id, $id));
    }

    /**
     * Mark all user's notifications as read.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function markAllRead(Request $request)
    {
        $request->user()
            ->unreadNotifications()
            ->get()->each(function ($n) {
            $n->markAsRead();
        });

        event(new NotificationReadAll($request->user()->id));
    }

    /**
     * Accepts the quest following a notification, including all appropriate actions
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request, $id)
    {
        $notification = $request->user()->unreadNotifications()->where('id', $id)->first();

        if ($notification) {
            switch ($notification->type) {
                case 'App\Notifications\UserWantsToJoinOrganization':
                    // Get payload and accept request
                    $payload  = $notification->data;
                    $sender   = User::find($payload['sender']['id']);
                    $receiver = Organization::find($payload['receiver']['id']);
                    $receiver->acceptFriendRequest($sender);

                    // Mark notification as read
                    $notification->markAsRead();
                    event(new NotificationRead($request->user()->id, $id));
                    return;
            }
        }
    }

    /**
     * Declines the quest following a notification, including all appropriate actions
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function decline(Request $request, $id)
    {
        $notification = $request->user()->unreadNotifications()->where('id', $id)->first();

        if ($notification) {
            switch ($notification->type) {
                case 'App\Notifications\UserWantsToJoinOrganization':
                    // Get payload and accept request
                    $payload  = $notification->data;
                    $sender   = User::find($payload['sender']['id']);
                    $receiver = Organization::find($payload['receiver']['id']);
                    $receiver->denyFriendRequest($sender);

                    // Mark notification as read
                    $notification->markAsRead();
                    event(new NotificationRead($request->user()->id, $id));
                    return;
            }
        }
    }
}
