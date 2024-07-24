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
            'notification_id' => 20035785712,
            'type' => 'SkyhookLostShields',
            'sender_id' => 1000137,
            'sender_type' => 'corporation',
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            'is_read' => true,
            'text' => "itemID: &id001 1045899550916\nplanetID: 40180016\nplanetShowInfoData:\n- showinfo\n- 2015\n- 40180016\nskyhookShowInfoData:\n- showinfo\n- 81080\n- id001\nsolarsystemID: 30002839\ntimeLeft: 1824005934062\ntimestamp: 133664381960000000\ntypeID: 81080\nvulnerableTime: 9000000000\n"
        ]);
        $this->info('Notification created.');
    }
}
