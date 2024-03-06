<?php

namespace Helious\SeatNotificationsPlus\Notifications\Structures;

use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Helious\SeatNotificationsPlus\Traits\attachmentNotificationTools;

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
class OrbitalAttacked extends AbstractDiscordNotification
{
    use attachmentNotificationTools;

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
            $attacker = !empty($this->notification->text['aggressorAllianceID']) ? $this->zKillBoardToDiscordLink('alliance',$this->notification->text['aggressorAllianceID'],UniverseName::firstOrNew(['entity_id' => $this->notification->text['aggressorAllianceID']],['category' => 'alliance', 'name' => trans('web::seat.unknown')])->name):$this->zKillBoardToDiscordLink('corporation',$this->notification->text['aggressorCorpID'],UniverseName::firstOrNew(['entity_id' => $this->notification->text['aggressorCorpID']],['category' => 'corporation', 'name' => trans('web::seat.unknown')] )->name);
            $system = MapDenormalize::find($this->notification->text['solarSystemID']);
            $region = Region::find($system->regionID)->name;
            $type = InvType::find($this->notification->text['typeID']);
            $planet = MapDenormalize::find($this->notification->text['planetID']);
            
            $embed->color('danger');
            $embed->author($corpName, 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
            $embed->title('Orbital under attack');
            $embed->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128');
            $embed->description("The {$type->typeName} at {$planet->name} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) is under attack by {$attacker}.");
            $embed->timestamp($this->notification->timestamp);
        });
    }
}