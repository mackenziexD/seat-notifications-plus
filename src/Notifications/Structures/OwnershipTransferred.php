<?php

namespace Helious\SeatNotificationsPlus\Notifications\Structures;

use Illuminate\Notifications\Messages\SlackMessage;
use Helious\SeatNotificationsPlus\Traits\attachmentNotificationTools;
use Helious\SeatNotificationsPlus\Notifications\AbstractNotification;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Eveapi\Models\Sde\Region;
use Carbon\Carbon;

/**
 * Class StructureUnderAttack.
 *
 */
class OwnershipTransferred extends AbstractNotification
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
            $system = MapDenormalize::find($this->notification->text['solarSystemID']);
            $region = Region::find($system->regionID)->name;
            $structureData = UniverseStructure::find($this->notification->text['structureID']);
            $structureName = $structureData ? $structureData->name : 'Unknown Structure';
            $type = InvType::find($this->notification->text['structureTypeID']);
            $old = UniverseName::firstOrNew(
                ['entity_id' => $this->notification->text['oldOwnerCorpID']],
                ['name' => trans('web::seat.unknown')]
            );
            $new = UniverseName::firstOrNew(
                ['entity_id' => $this->notification->text['newOwnerCorpID']],
                ['name' => trans('web::seat.unknown')]
            );
            $char = UniverseName::firstOrNew(
                ['entity_id' => $this->notification->text['charID']],
                ['name' => trans('web::seat.unknown')]
            );

            $attachment->author($corpName, '', 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128')
            ->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128')
            ->title('Ownership transferred')
            ->content("The {$type->typeName} **{$structureName}** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) has been transfered from {$this->zKillBoardToDiscordLink('corporation', $old->entity_id, $old->name)} to {$this->zKillBoardToDiscordLink('corporation', $new->entity_id, $new->name)} by {$char->name}.")
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