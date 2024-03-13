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
use Seat\Eveapi\Models\Sde\Region;

/**
 * Class StructureUnderAttack.
 *
 */
class MoonminingExtractionFinished extends AbstractDiscordNotification
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
            $type = InvType::find($this->notification->text['structureTypeID']);
            $planet = MapDenormalize::find($this->notification->text['moonID']);
            $structureName = $this->notification->text['structureName'];
            $autoFrac = $this->ldapToDateTime($this->notification->text['autoTime']);

            $description = "The extraction for **{$structureName}** at {$planet->itemName} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) is finished and the chunk is ready to be shot at. The chunk will automatically fracture on **{$autoFrac}**.\n\n";
            
            foreach($this->notification->text['oreVolumeByType'] as $typeID => $volume) {
                $type2 = InvType::find($typeID);
                $description .= "- {$type2->typeName}: ".number_format($volume, 2)." m³\n";
            }

            $embed->color(DiscordMessage::INFO);
            $embed->author($corpName, 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
            $embed->title('Moon Mining Extraction Finished');
            $embed->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128');
            $embed->description($description);
            $embed->timestamp($this->notification->timestamp);
        });
    }
}