<?php

namespace Helious\SeatNotificationsPlus\Notifications\Towers;

use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Helious\SeatNotificationsPlus\Traits\EmbedNotificationTools;
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
class TowerResourceAlertMsg extends AbstractDiscordNotification
{
    use EmbedNotificationTools, StarbaseUtilities;

    private CharacterNotification $notification;

    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    public function has_sov($systemID, $allianceID) {
        $sovereignty = SovereigntyMap::where('system_id', $systemID)->first();
        \Log::error($sovereignty->alliance->entity_id);
        if ($sovereignty) {
            return $sovereignty->alliance->entity_id == $allianceID;
        }
        return false;
    }

    public function populateMessage(DiscordMessage $message, $notifiable)
    {
        $message->embed(function (DiscordEmbed $embed) {
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

            $embed->color(DiscordMessage::WARNING);
            $embed->author($corpName, 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
            $embed->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128');
            $embed->title("{$type->group->groupName} fuel alert");
            $embed->description("The {$type->typeName} at {$planet->name} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) is running out of fuel in: **{$FuelLeft}**");
            $embed->timestamp($this->notification->timestamp);
        });
    }
}