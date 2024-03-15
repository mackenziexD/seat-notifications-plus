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
            "character_id" => 94154296,
            "notification_id" => 1000000919,
            "type" => "EntosisCaptureStarted",
            "sender_id" => 3001,
            "sender_type" => "corporation",
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            "text" => "solarSystemID: 30001821\nstructureTypeID: 32458\n",
            "is_read" => false
        ]);
        $this->info('Notification created.');
    }

}