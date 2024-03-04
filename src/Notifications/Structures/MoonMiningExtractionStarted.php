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
use Seat\Eveapi\Models\Sde\Planet;

/**
 * Class StructureUnderAttack.
 *
 */
class MoonMiningExtractionStarted extends AbstractDiscordNotification
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
            $type = InvType::find($this->notification->text['structureTypeID']);
            $planet = MapDenormalize::find($this->notification->text['moonID']);
            $structureName = $this->notification->text['structureName'];
            $char = UniverseName::firstOrNew(
                ['entity_id' => $this->notification->text['startedBy']],
                ['name' => trans('web::seat.unknown')]
            );
            $readyAt = $this->ldapToDateTime($this->notification->text['readyTime']);
            $autoFrac = $this->ldapToDateTime($this->notification->text['autoTime']);

            $description = "A moon mining extraction has been started for **{$structureName}** at {$planet} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) Extraction was started by ${$char->name}. the chuck will be ready on location at ${$readyAt} and will be automatically fractured at ${$autoFrac}, and will auto fracture on ${$autoFrac}.\n\n";
            
            foreach($this->notification->text['oreVolumeByType'] as $typeID => $volume) {
                $type = InvType::find($typeID);
                $description .= "- {$type->typeName}: {number_format($volume, 2)} m³\n";
            }

            $embed->color(DiscordMessage::INFO);
            $embed->author('{$corpName}', 'https://images.evetech.net/corporations/{$corpID}/logo?size=128');
            $embed->title('Moon Mining Extraction Started');
            $embed->thumb('https://images.evetech.net/types/{$type->typeID}/icon?size=128');
            $embed->description($description);
            $embed->timestamp($this->notification->timestamp);
        });
    }
}