<?php

namespace App\Console\Commands;

use App\Notifications\UserWantsToJoinOrganization;
use App\Organization;
use App\User;
use Illuminate\Console\Command;

class SendEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Raise an event';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user         = User::find(1);
        $organization = Organization::find(1);
        $recipient    = User::find($organization->owner_id);
        // Notification::create([
        //     'sender_id'     => $user->id,
        //     'sender_type'   => User::class,
        //     'receiver_id'   => $organization->id,
        //     'receiver_type' => Organization::class,
        //     'type'          => 'organization.userwantstojoin',
        // ]);
        // event(new UserWantsToJoinOrganization($user, $organization));
        $recipient->notify(new UserWantsToJoinOrganization($user, $organization));
    }
}
