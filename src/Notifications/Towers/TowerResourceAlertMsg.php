<?php

namespace Helious\SeatNotificationsPlus\Notifications\Towers;


use Illuminate\Notifications\Messages\SlackMessage;
use Helious\SeatNotificationsPlus\Notifications\AbstractNotification;
use Helious\SeatNotificationsPlus\Traits\attachmentNotificationTools;
use Helious\SeatNotificationsPlus\Traits\StarbaseUtilities;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Eveapi\Models\Sde\Planet;
use Seat\Eveapi\Models\Sde\Region;
use Seat\Eveapi\Models\Sovereignty\SovereigntyMap;
use Carbon\Carbon;

/**
 * Class EntosisCaptureStarted.
 *
 */
class TowerResourceAlertMsg extends AbstractNotification
{
    use attachmentNotificationTools, StarbaseUtilities;

    private CharacterNotification $notification;

    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function has_sov($systemID, $allianceID) {
        $sovereignty = SovereigntyMap::where('system_id', $systemID)->first();
        \Log::error($sovereignty->alliance->entity_id);
        if ($sovereignty) {
            return $sovereignty->alliance->entity_id == $allianceID;
        }
        return false;
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->attachment(function ($attachment) {
                $corpName = $this->notification->recipient->affiliation->corporation->name;
                $corpID = $this->notification->recipient->affiliation->corporation_id;
                $system = MapDenormalize::find($this->notification->text['solarSystemID']);
                $region = Region::find($system->regionID)->name;
                $type = InvType::find($this->notification->text['typeID']);
                $planet = MapDenormalize::find($this->notification->text['moonID']);
                $fuelQuantity = $this->notification->text['wants'][0]['quantity'];

                $sovDiscount = $this->has_sov($system->itemID, $this->notification->text['allianceID']);
                try {
                    $fuelDurationSeconds = StarbaseUtilities::fuelDuration($type->typeName, $fuelQuantity, $sovDiscount);
                    $fuelEndTime = Carbon::now()->addSeconds($fuelDurationSeconds);
                    $FuelLeft = $fuelEndTime->diffForHumans(null, true, false, 2) . ' from now';
                } catch (\Exception $e) {
                    $FuelLeft = "Unknown";
                }

                $attachment->color('warning')
                ->author($corpName, '', 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128')
                ->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128')
                ->title("{$type->group->groupName} fuel alert")
                ->content("The {$type->typeName} at {$planet->name} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) is running out of fuel in: **{$FuelLeft}**")
                ->timestamp(Carbon::createFromFormat('Y-m-d H:i:s', $this->notification->timestamp)->getTimestamp());
            });
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->notification->text;
    }
}