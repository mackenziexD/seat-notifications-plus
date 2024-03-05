<?php

namespace Helious\SeatNotificationsPlus\Console;

use Illuminate\Console\Command;
use Helious\SeatNotificationsPlus\Models\SeatNotificationsPlus;
use Seat\Eveapi\Models\Character\CharacterNotification;


/**
 * Class RemindOperation.
 *
 * @package Seat\Kassie\Calendar\Commands
 */
class TestObs extends Command
{
    
    /**
     * @var string
     */
    protected $signature = 'test:notif';

    /**
     * @var string
     */
    protected $description = 'Checks for beacons offline or low on fuel.';

    /**
     * Process the command.
     */
    public function handle()
    {
        CharacterNotification::create([
            'character_id' => 2116795743,
            'notification_id' => 1923730118,
            'type' => 'StructureLostShields',
            'sender_id' => 1000137,
            'sender_type' => 'corporation',
            'timestamp' => '2024-03-05 01:49:00',
            'is_read' => 0,
            'text' => "solarsystemID: 30004068\nstructureID: &id001 1043652648031\nstructureShowInfoData:\n- showinfo\n- 35835\n- *id001\nstructureTypeID: 35835\ntimeLeft: 3612236828034\ntimestamp: 133486337110000000\nvulnerableTime: 9000000000"
        ]);
        $this->info('Notification created.');
    }

}