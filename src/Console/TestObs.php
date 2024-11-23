<?php

namespace Helious\SeatNotificationsPlus\Console;

use Illuminate\Console\Command;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Carbon\Carbon;

/**
 * Class TestObs.
 *
 * @package Helious\SeatNotificationsPlus\Console
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
            'character_id' => 2120410723,
            'notification_id' => 200357878969,
            'type' => 'MercenaryDenReinforced',
            'sender_id' => 1000438,
            'sender_type' => 'corporation',
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            'is_read' => true,
            'text' => "aggressorAllianceName: <a href=\"showinfo:16159//99011223\">We Forsakened Few</a>\naggressorCharacterID: 2112890910\naggressorCorporationName: <a href=\"showinfo:2//98637264\">The Forsakened Few</a>\nitemID: &id001 1047221349878\nmercenaryDenShowInfoData:\n- showinfo\n- 85230\n- *id001\nplanetID: 40206110\nplanetShowInfoData:\n- showinfo\n- 11\n- 40206041\nsolarsystemID: 30003249\ntimestampEntered: 133768506856482481\ntimestampExited: 133769535156482481\ntypeID: 85230\n"
        ]);
        $this->info('Notification created.');
    }
}
