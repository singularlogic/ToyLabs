<?php

return [

    'tables' => [
        'fr_pivot'        => 'memberships',
        'fr_groups_pivot' => 'user_membership_groups',
    ],

    'groups' => [
        'owner'  => 0,
        'admin'  => 1,
        'member' => 2,
        'friend' => 3,
    ],

];
