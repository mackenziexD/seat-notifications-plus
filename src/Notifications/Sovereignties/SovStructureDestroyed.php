<?php

namespace Helious\SeatNotificationsPlus\Notifications\Sovereignties;

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
use Seat\Eveapi\Models\Sde\Constellation;

/**
 * Class EntosisCaptureStarted.
 *
 */
class SovStructureDestroyed extends AbstractDiscordNotification
{
    use EmbedNotificationTools;

    private CharacterNotification $notification;

    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    public function populateMessage(DiscordMessage $message, $notifiable)
    {
        
        $message->from('Upwell Consortium', '');
        $message->embed(function (DiscordEmbed $embed) {
            $corpName = $this->notification->recipient->affiliation->corporation->name;
            $corpID = $this->notification->recipient->affiliation->corporation_id;
            $system = MapDenormalize::find($this->notification->text['solarSystemID']);
            $region = Region::find($system->regionID)->name;
            $type = InvType::find($this->notification->text['structureTypeID']);
            
            $embed->color(DiscordMessage::ERROR);
            $embed->author($corpName, 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
            $embed->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128');
            $embed->title("{$type->group->groupName} in {$system->itemName} has been destroyed");
            $embed->description("The command nodes for {$type->typeName} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) have been destroyed by hostile forces.");
            $embed->timestamp($this->notification->timestamp);
        });
    }
}