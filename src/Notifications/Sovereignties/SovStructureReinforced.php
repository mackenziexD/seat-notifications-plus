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

/**
 * Class EntosisCaptureStarted.
 *
 */
class SovStructureReinforced extends AbstractDiscordNotification
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
            $deloakTime = $this->ldapToDateTime($this->notification->text['decloakTime']);
            
            $embed->color(DiscordMessage::ERROR);
            $embed->author($corpName, 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
            if($this->notification->text['campaignEventType'] === 1){
                // TCU Event
                $embed->thumb('https://images.evetech.net/types/32226/icon?size=128');
                $embed->title("Territorial Claim Unit in {$system->itemName} has entered reinforced mode");
                $embed->description("The **Territorial Claim Unit** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) has been reinforced by hostile forces and command nodes will begin decloaking at **{$deloakTime}**");
            } elseif ($this->notification->text['campaignEventType'] === 2){
                // IHUB Event
                $embed->thumb('https://images.evetech.net/types/32458/icon?size=128');
                $embed->title("Infrastructure Hub in {$system->itemName} has entered reinforced mode");
                $embed->description("The **Infrastructure Hub** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) has been reinforced by hostile forces and command nodes will begin decloaking at **{$deloakTime}**");
            } else {
                // Unknown Event
                $embed->thumb('https://images.evetech.net/types/0/icon?size=128');
                $embed->title("Unknown Structure in {$system->itemName} has entered reinforced mode");
                $embed->description("The an **Unknown Structure** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) has been reinforced by hostile forces and command nodes will begin decloaking at **{$deloakTime}**");
            }
            $embed->timestamp($this->notification->timestamp);
        });
    }
}