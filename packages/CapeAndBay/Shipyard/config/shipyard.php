<?php

// @todo - remove trufit related junk, relegate to V3's own version of the config
return [
    'push_notifications' => [
        'enabled' => true,
        'notifiable_model' => CapeAndBay\Shipyard\PushNotifiables::class,
        // The Model Schema that are filterable
        'notifiable_model_filters' => [
            'home_club_id' => [
                'name' => 'Home Club',
                'type' => 'list',
                'relation' => 'home_club',
                'column' => 'name'
            ],
            'membership_plan' => [
                'name' => 'Membership Plan',
                'type' => 'list',
                'relation' => false,
                'column' => 'membership_plan'
            ],
        ],
        // Currently supported - expo, firebase, & none
        'drivers' => ['expo']
    ]
];
