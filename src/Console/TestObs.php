<?php

namespace Helious\SeatNotificationsPlus\Console;

use Illuminate\Console\Command;
use Helious\SeatNotificationsPlus\Models\SeatNotificationsPlus;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Carbon\Carbon;

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
            'character_id' => 94154296,
            'notification_id' => 1923730118,
            'type' => 'StructureWentHighPower',
            'sender_id' => 1000137,
            'sender_type' => 'corporation',
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            'is_read' => 0,
            'text' => "solarsystemID: 30003235\nstructureID: &id001 1044596949033\nstructureShowInfoData:\n- showinfo\n- 35841\n- *id001\nstructureTypeID: 35841\n"
        ]);
        $this->info('Notification created.');
    }

}