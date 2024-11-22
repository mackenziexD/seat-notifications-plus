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
            'character_id' => 94154296,
            'notification_id' => 200357857171,
            'type' => 'MercenaryDenReinforced',
            'sender_id' => 1000137,
            'sender_type' => 'corporation',
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            'is_read' => true,
            'text' => "aggressorAllianceName: <a href=\"showinfo:16159//99011223\">Sigma Grindset</a>\naggressorCharacterID: 92245397\naggressorCorporationName: <a href=\"showinfo:2//98637264\">Sensible People</a>\nitemID: &id001 1047221349878\nmercenaryDenShowInfoData:\n- showinfo\n- 85230\n- *id001\nplanetID: 40206041\nplanetShowInfoData:\n- showinfo\n- 11\n- 40206041\nsolarsystemID: 30003247\ntimestampEntered: 133763522581585101\ntimestampExited: 133764277671585101\ntypeID: 85230\n"
        ]);
        $this->info('Notification created.');
    }
}
