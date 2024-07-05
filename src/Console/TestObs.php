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
            'character_id' => 2114464253,
            'notification_id' => 1993510159,
            'type' => 'unknown notification type (283)',
            'sender_id' => 1000137,
            'sender_type' => 'corporation',
            'timestamp' => Carbon::parse('2024-07-05T01:14:00Z')->format('Y-m-d H:i:s'),
            'is_read' => true,
            'text' => "allianceID: 99005338\nallianceLinkData:\n- showinfo\n- 16159\n- 99005338\nallianceName: Pandemic Horde\narmorPercentage: 100.0\ncharID: 2114464253\ncorpLinkData:\n- showinfo\n- 2\n- 2014367342\ncorpName: Blackwater USA Inc.\nhullPercentage: 100.0\nisActive: true\nitemID: &id001 1045848302693\nplanetID: 40206108\nplanetShowInfoData:\n- showinfo\n- 2015\n- 40206108\nshieldPercentage: 94.92754468794297\nskyhookShowInfoData:\n- showinfo\n- 81080\n- *id001\nsolarsystemID: 30003249\ntypeID: 81080\n"
        ]);
        $this->info('Notification created.');
    }
}
