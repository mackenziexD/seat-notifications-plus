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
use Seat\Eveapi\Models\Sovereignty\SovereigntyMap;
use Seat\Eveapi\Models\Sde\Planet;
use Seat\Eveapi\Models\Sde\Region;

/**
 * Class EntosisCaptureStarted.
 *
 */
class EntosisCaptureStarted extends AbstractDiscordNotification
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

            $alliance = SovereigntyMap::find($this->notification->text['solarSystemID'])->alliance;
            $allianceId = $alliance ? (int) $alliance->entity_id : null;
            $owner = $this->zKillBoardToDiscordLink('alliance', $allianceId, $alliance ? $alliance->name : '**Unknown**');

            $type = InvType::find($this->notification->text['structureTypeID']);
            
            $embed->color(DiscordMessage::WARNING);
            $embed->author($corpName, 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
            $embed->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128');
            $embed->title("{$type->group->groupName} in {$system->itemName} is being captured");
            $embed->description("A capsuleer has started to influence the {$type->typeName} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) belonging to {$owner} with an Entosis Link.");
            $embed->timestamp($this->notification->timestamp);
        });
    }
}