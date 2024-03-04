<?php

return [
    'AllAnchoringMsg [N+]' => [
        'label' => 'AllAnchoringMsg [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\AllAnchoringMsg::class
        ],
    ],
    'EntosisCaptureStarted [N+]' => [
        'label' => 'EntosisCaptureStarted [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Sovereignties\EntosisCaptureStarted::class
        ],
    ],
    'MoonminingExtractionFinished1111 [N+]' => [
        'label' => 'MoonminingExtractionFinished1111 [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\MoonMiningExtractionFinished::class
        ],
    ],
    'MoonminingExtractionStarted [N+]' => [
        'label' => 'MoonminingExtractionStarted [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\MoonMiningExtractionStarted::class
        ],
    ],
    'OrbitalAttacked [N+]' => [
        'label' => 'OrbitalAttacked [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\OrbitalAttacked::class
        ],
    ],
    'OwnershipTransferred [N+]' => [
        'label' => 'OwnershipTransferred [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\OwnershipTransferred::class
        ],
    ],
    'SovCommandNodeEventStarted [N+]' => [
        'label' => 'SovCommandNodeEventStarted [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Sovereignties\SovCommandNodeEventStarted::class
        ],
    ],
    'SovStructureDestroyed [N+]' => [
        'label' => 'SovStructureDestroyed [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Sovereignties\SovStructureDestroyed::class
        ],
    ],
    'SovStructureReinforced [N+]' => [
        'label' => 'SovStructureReinforced [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Sovereignties\SovStructureReinforced::class
        ],
    ],
    'StructureAnchoring [N+]' => [
        'label' => 'StructureAnchoring [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureAnchoring::class
        ],
    ],
    'StructureDestroyed [N+]' => [
        'label' => 'StructureDestroyed [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureDestroyed::class
        ],
    ],
    'StructureFuelAlert [N+]' => [
        'label' => 'StructureFuelAlert [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureFuelAlert::class
        ],
    ],
    'StructureLostArmor [N+]' => [
        'label' => 'StructureLostArmor [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureLostArmor::class
        ],
    ],
    'StructureLostShields [N+]' => [
        'label' => 'StructureLostShields [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureLostShields::class
        ],
    ],
    'StructureServicesOffline [N+]' => [
        'label' => 'StructureServicesOffline [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureServicesOffline::class
        ],
    ],
    'StructureUnanchoring [N+]' => [
        'label' => 'StructureUnanchoring [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureUnanchoring::class
        ],
    ],
    'StructureUnderAttack [N+]' => [
        'label' => 'StructureUnderAttack [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureUnderAttack::class
        ],
    ],
    'StructureWentHighPower [N+]' => [
        'label' => 'StructureWentHighPower [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureWentHighPower::class
        ],
    ],
    'StructureWentLowPower [N+]' => [
        'label' => 'StructureWentLowPower [N+]',
        'handlers' => [
            'discord' => \Seat\Notifications\Notifications\Structures\StructureWentLowPower::class
        ],
    ]
];