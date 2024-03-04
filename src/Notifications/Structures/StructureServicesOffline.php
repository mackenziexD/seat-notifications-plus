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
use Seat\Eveapi\Models\Universe\UniverseStructure;

/**
 * Class StructureUnderAttack.
 *
 */
class StructureServicesOffline extends AbstractDiscordNotification
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
            $corpName = $this->notification->recipient->corporation->name;
            $corpID = $this->notification->recipient->corporation->corporation_id;
            $system = MapDenormalize::find($this->notification->text['solarsystemID']);
            $region = MapDenormalize::find($system->regionID)->region->name;
            $structureData = UniverseStructure::find($this->notification->text['structureID']);
            $structureName = $structureData ? $structureData->name : 'Unknown Structure';
            $type = InvType::find($this->notification->text['structureShowInfoData'][1]);
            $description = "The {$type->typeName} **{$structureName}** in {$this->zKillBoardToDiscordLink('system', $system->itemID, $system->itemName)} ({$region}) has all services off-lined.";
            foreach ($this->notification->text['listOfServiceModuleIDs'] as $type_id) {
                $type = InvType::find($type_id);
                $description .= "\n" . $type->typeName;
            }

            $embed->color(DiscordMessage::INFO);
            $embed->author('{$corpName}', 'https://images.evetech.net/corporations/{$corpID}/logo?size=128');
            $embed->title('Structure Services Offline');
            $embed->thumb('https://images.evetech.net/types/{$type->typeID}/icon?size=128');
            $embed->description($description);
            $embed->timestamp($this->notification->timestamp);
        });
    }
}