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
class SovCommandNodeEventStarted extends AbstractDiscordNotification
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
            $const = MapDenormalize::find($this->notification->text['constellationID']);
            $region = Region::find($system->regionID)->name;
            
            $embed->color(DiscordMessage::WARNING);
            $embed->author($corpName, 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
            if($this->notification->text['campaignEventType'] === 1){
                // TCU Event
                $embed->thumb('https://images.evetech.net/types/32226/icon?size=128');
                $embed->title("Command nodes for Territorial Claim Unit in {$system->itemName} have begun to decloak");
                $embed->description("Command nodes for **Territorial Claim Unit** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) can now be found throughout the {$const->name} constellation");
            } elseif ($this->notification->text['campaignEventType'] === 2){
                // IHUB Event
                $embed->thumb('https://images.evetech.net/types/32458/icon?size=128');
                $embed->title("Command nodes for Infrastructure Hub in {$system->itemName} have begun to decloak");
                $embed->description("Command nodes for **Infrastructure Hub** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) can now be found throughout the {$const->name} constellation");
            } else {
                // Unknown Event
                $embed->thumb('https://images.evetech.net/types/0/icon?size=128');
                $embed->title("Command nodes for Unknown Structure in {$system->itemName} have begun to decloak");
                $embed->description("Command nodes for an **Unknown Structure** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) can now be found throughout the {$const->name} constellation");
            }
            $embed->timestamp($this->notification->timestamp);
        });
    }
}