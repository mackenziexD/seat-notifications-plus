<?php

namespace Helious\SeatNotificationsPlus\Notifications\Structures;

use Illuminate\Notifications\Messages\SlackMessage;
use Helious\SeatNotificationsPlus\Traits\attachmentNotificationTools;
use Seat\Notifications\Notifications\AbstractNotification;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Eveapi\Models\Sde\Region;
use Carbon\Carbon;

/**
 * Class StructureUnderAttack.
 *
 */
class StructureServicesOffline extends AbstractNotification
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
            $structureData = UniverseStructure::find($this->notification->text['structureID']);
            $structureName = $structureData ? $structureData->name : 'Unknown Structure';
            $type = InvType::find($this->notification->text['structureShowInfoData'][1]);
            $description = "The {$type->typeName} **{$structureName}** in {$this->zKillBoardToDiscordLink('system', $system->itemID, $system->itemName)} ({$region}) has all services off-lined.\n";
            foreach ($this->notification->text['listOfServiceModuleIDs'] as $type_id) {
                $type2 = InvType::find($type_id);
                $description .= "- " . $type2->typeName . "\n";
            }

            $attachment->color('danger')
            ->author($corpName, '', 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128')
            ->title('Structure Services Offline')
            ->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128')
            ->content($description)
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