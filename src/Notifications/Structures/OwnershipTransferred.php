<?php

namespace Helious\SeatNotificationsPlus\Notifications\Structures;

use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Helious\SeatNotificationsPlus\Traits\EmbedNotificationTools;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Eveapi\Models\Sde\Region;

/**
 * Class StructureUnderAttack.
 *
 */
class OwnershipTransferred extends AbstractDiscordNotification
{
    use EmbedNotificationTools;

    private CharacterNotification $notification;

    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    public function populateMessage(DiscordMessage $message, $notifiable)
    {       
        $message->embed(function (DiscordEmbed $embed) {
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
            $embed->color(DiscordMessage::INFO);
            $embed->author($corpName, 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
            $embed->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128');
            $embed->title('Ownership transferred');
            $embed->description("The {$type->typeName} **{$structureName}** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) has been transfered from {$this->zKillBoardToDiscordLink('corporation', $old->entity_id, $old->name)} to {$this->zKillBoardToDiscordLink('corporation', $new->entity_id, $new->name)} by {$char->name}.");
            $embed->timestamp($this->notification->timestamp);
        });
    }
}