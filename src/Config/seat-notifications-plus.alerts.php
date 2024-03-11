<?php

return [
    'AllAnchoringMsg [N+]' => [
        'label' => 'AllAnchoringMsg [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\AllAnchoringMsg::class
        ],
    ],
    'EntosisCaptureStarted [N+]' => [
        'label' => 'EntosisCaptureStarted [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Sovereignties\EntosisCaptureStarted::class
        ],
    ],
    'MoonminingExtractionFinished [N+]' => [
        'label' => 'MoonminingExtractionFinished [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\MoonminingExtractionFinished::class
        ],
    ],
    'MoonminingExtractionStarted [N+]' => [
        'label' => 'MoonminingExtractionStarted [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\MoonminingExtractionStarted::class
        ],
    ],
    'OrbitalAttacked [N+]' => [
        'label' => 'OrbitalAttacked [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\OrbitalAttacked::class
        ],
    ],
    'OwnershipTransferred [N+]' => [
        'label' => 'OwnershipTransferred [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\OwnershipTransferred::class
        ],
    ],
    'SovCommandNodeEventStarted [N+]' => [
        'label' => 'SovCommandNodeEventStarted [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Sovereignties\SovCommandNodeEventStarted::class
        ],
    ],
    'SovStructureDestroyed [N+]' => [
        'label' => 'SovStructureDestroyed [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Sovereignties\SovStructureDestroyed::class
        ],
    ],
    'SovStructureReinforced [N+]' => [
        'label' => 'SovStructureReinforced [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Sovereignties\SovStructureReinforced::class
        ],
    ],
    'StructureAnchoring [N+]' => [
        'label' => 'StructureAnchoring [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\StructureAnchoring::class
        ],
    ],
    'StructureDestroyed [N+]' => [
        'label' => 'StructureDestroyed [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\StructureDestroyed::class
        ],
    ],
    'StructureFuelAlert [N+]' => [
        'label' => 'StructureFuelAlert [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\StructureFuelAlert::class
        ],
    ],
    'StructureLostArmor [N+]' => [
        'label' => 'StructureLostArmor [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\StructureLostArmor::class
        ],
    ],
    'StructureLostShields [N+]' => [
        'label' => 'StructureLostShields [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\StructureLostShields::class
        ],
    ],
    'StructureServicesOffline [N+]' => [
        'label' => 'StructureServicesOffline [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\StructureServicesOffline::class
        ],
    ],
    'StructureUnanchoring [N+]' => [
        'label' => 'StructureUnanchoring [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\StructureUnanchoring::class
        ],
    ],
    'StructureUnderAttack [N+]' => [
        'label' => 'StructureUnderAttack [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\StructureUnderAttack::class
        ],
    ],
    'StructureWentHighPower [N+]' => [
        'label' => 'StructureWentHighPower [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\StructureWentHighPower::class
        ],
    ],
    'StructureWentLowPower [N+]' => [
        'label' => 'StructureWentLowPower [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Structures\StructureWentLowPower::class
        ],
    ],
    'TowerResourceAlertMsg [N+]' => [
        'label' => 'TowerResourceAlertMsg [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Towers\TowerResourceAlertMsg::class
        ],
    ],
    'TowerAlertMsg [N+]' => [
        'label' => 'TowerAlertMsg [N+]',
        'handlers' => [
            'slack' => \Helious\SeatNotificationsPlus\Notifications\Towers\TowerAlertMsg::class
        ],
    ]
];