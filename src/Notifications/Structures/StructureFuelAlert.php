<?php

namespace Helious\SeatNotificationsPlus\Notifications\Structures;

use Illuminate\Notifications\Messages\SlackMessage;
use Helious\SeatNotificationsPlus\Traits\attachmentNotificationTools;
use Seat\Notifications\Notifications\AbstractNotification;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Eveapi\Models\Corporation\CorporationStructure;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Eveapi\Models\Sde\Region;
use Carbon\Carbon;

/**
 * Class StructureUnderAttack.
 *
 */
class StructureFuelAlert extends AbstractNotification
{
    use attachmentNotificationTools;

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
            $system = MapDenormalize::find($this->notification->text['solarsystemID']);
            $region = Region::find($system->regionID)->name;
            $type = InvType::find($this->notification->text['structureTypeID']);
            $structureData = UniverseStructure::find($this->notification->text['structureID']);
            $structureName = $structureData ? $structureData->name : 'Unknown Structure';
            $fuelLeft = CorporationStructure::find($this->notification->text['structureID'])->fuel_expires ?? 'Unknown';
            
            $attachment->color('warning')
            ->author($corpName, '', 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128')
            ->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128')
            ->title('Structure Fuel Alert')
            ->content("The {$type->typeName} **{$structureName}** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) is going to run out of fuel at: **{$fuelLeft}**.")
            ->timestamp(Carbon::createFromFormat('Y-m-d H:i:s', $this->notification->timestamp));
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