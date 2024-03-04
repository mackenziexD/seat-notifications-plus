<?php

return [
    'notifications' => [
        'name' => 'Notfications+',
        'icon' => 'fas fa-bell',
        'route_segment' => 'notifications-plus',
        'entries' => [
            [
                'name' => 'Settings',
                'icon' => 'fab fa-black-tie',
                'route' => 'seat-notifications::settings',
                'permission' => [
                    'seat-notfications.access',
                ],
            ],
        ],
    ],
];