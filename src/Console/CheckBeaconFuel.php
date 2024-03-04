<?php

namespace Helious\SeatNotificationsPlus\Console;

use Illuminate\Console\Command;
use Seat\Eveapi\Models\Corporation\CorporationStructure;
use Seat\Eveapi\Models\Corporation\CorporationStructureService;
use Carbon\Carbon;
use Seat\Notifications\Models\NotificationGroup;
use Illuminate\Support\Facades\Notification;
use Helious\SeatNotificationsPlus\Notifications\StuctureWarnings;
use Seat\Notifications\Traits\NotificationDispatchTool;
use Seat\Web\Models\User;


/**
 * Class RemindOperation.
 *
 * @package Seat\Kassie\Calendar\Commands
 */
class CheckBeaconFuel extends Command
{
    use NotificationDispatchTool;
    
    /**
     * @var string
     */
    protected $signature = 'beacons:fuel';

    /**
     * @var string
     */
    protected $description = 'Checks for beacons offline or low on fuel.';

    /**
     * Process the command.
     */
    public function handle()
    {
        $structures = CorporationStructure::where('type_id', '35840')->get();
        $structureMessage = '';

        foreach ($structures as $structure) {
            $services = $structure->services->first();
            if ($services->state === 'online') {
                $fuel_expires = Carbon::parse($structure->fuel_expires);
                $days_left = $fuel_expires->diffInDays();
                if ($days_left <= 7) {
                    $structureMessage .= '`'. $structure->info->name . ': ' . $days_left . ' Days Left `'. PHP_EOL;
                }
            } else {
                $structureMessage .= '`'. $structure->info->name . ': OFFLINE `'. PHP_EOL;
            }

        }

        // dont send empty message
        if($structureMessage === '') return;

        $this->dispatch($structureMessage);
    }
    
    /**
     * Queue notification based on User Creation.
     *
     * @param $structureMessage
     */
    private function dispatch($structureMessage)
    {

        // Get notification groups with 'seat_beacons_warnings' alert type
        $groups = NotificationGroup::with('alerts')
            ->whereHas('alerts', function ($query) {
                $query->where('alert', 'seat_beacons_warnings');
            })->get();

        $this->dispatchNotifications('seat_beacons_warnings', $groups, function ($notificationClass) use ($structureMessage) {
            return new $notificationClass($structureMessage);
        });

    }
}