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
            'type' => 'MoonminingLaserFired',
            'sender_id' => 1000137,
            'sender_type' => 'corporation',
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            'is_read' => 0,
            'text' => "firedBy: 2115784309\nfiredByLink: <a href=\"showinfo:1386//2115784309\">CJ Kondur</a>\nmoonID: 40204351\noreVolumeByType:\n  45494: 8364045.526322877\n  45498: 10867302.250553984\n  45512: 4960061.982821797\nsolarSystemID: 30003222\nstructureID: 1038565229449\nstructureLink: <a href=\"showinfo:35835//1038565229449\">Q-ITV5 - A38 P4M3</a>\nstructureName: Q-ITV5 - A38 P4M3\nstructureTypeID: 35835\n"
        ]);
        $this->info('Notification created.');
    }

}