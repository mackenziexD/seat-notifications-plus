<?php

return [
    'AllAnchoringMsg [N+]' => [
        'label' => 'notifications::alerts.alliance_anchoring',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\AllAnchoringMsg::class
        ],
    ],
    'EntosisCaptureStarted [N+]' => [
        'label' => 'notifications::alerts.entosis_capture_started',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Sovereignties\EntosisCaptureStarted::class
        ],
    ],
    'MoonminingExtractionFinished [N+]' => [
        'label' => 'notifications::alerts.moon_mining_extraction_finished',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\MoonMiningExtractionFinished::class
        ],
    ],
    'MoonminingExtractionStarted [N+]' => [
        'label' => 'notifications::alerts.moon_mining_extraction_started',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\MoonMiningExtractionStarted::class
        ],
    ],
    'OrbitalAttacked [N+]' => [
        'label' => 'notifications::alerts.orbital_attacked',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\OrbitalAttacked::class
        ],
    ],
    'OwnershipTransferred [N+]' => [
        'label' => 'notifications::alerts.ownership_transferred',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\OwnershipTransferred::class
        ],
    ],
    'SovCommandNodeEventStarted [N+]' => [
        'label' => 'notifications::alerts.sovereignty_command_node_event_started',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Sovereignties\SovCommandNodeEventStarted::class
        ],
    ],
    'SovStructureDestroyed [N+]' => [
        'label' => 'notifications::alerts.sovereignty_structure_destroyed',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Sovereignties\SovStructureDestroyed::class
        ],
    ],
    'SovStructureReinforced [N+]' => [
        'label' => 'notifications::alerts.sovereignty_structure_reinforced',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Sovereignties\SovStructureReinforced::class
        ],
    ],
    'StructureAnchoring [N+]' => [
        'label' => 'notifications::alerts.structure_anchoring',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureAnchoring::class
        ],
    ],
    'StructureDestroyed [N+]' => [
        'label' => 'notifications::alerts.structure_destroyed',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureDestroyed::class
        ],
    ],
    'StructureFuelAlert [N+]' => [
        'label' => 'notifications::alerts.structure_fuel_alert',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureFuelAlert::class
        ],
    ],
    'StructureLostArmor [N+]' => [
        'label' => 'notifications::alerts.structure_lost_armor',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureLostArmor::class
        ],
    ],
    'StructureLostShields [N+]' => [
        'label' => 'notifications::alerts.structure_lost_shield',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureLostShields::class
        ],
    ],
    'StructureServicesOffline [N+]' => [
        'label' => 'notifications::alerts.structure_services_offline',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureServicesOffline::class
        ],
    ],
    'StructureUnanchoring [N+]' => [
        'label' => 'notifications::alerts.structure_unanchoring',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureUnanchoring::class
        ],
    ],
    'StructureUnderAttack [N+]' => [
        'label' => 'notifications::alerts.structure_under_attack',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureUnderAttack::class
        ],
    ],
    'StructureWentHighPower [N+]' => [
        'label' => 'notifications::alerts.structure_went_high_power',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureWentHighPower::class
        ],
    ],
    'StructureWentLowPower [N+]' => [
        'label' => 'notifications::alerts.structure_went_low_power',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureWentLowPower::class
        ],
    ]
];