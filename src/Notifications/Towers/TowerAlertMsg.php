<?php

namespace Helious\SeatNotificationsPlus\Notifications\Towers;

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
class TowerAlertMsg extends AbstractDiscordNotification
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
            $attacker = !is_null($this->notification->text['aggressorAllianceID']) ? $this->zKillBoardToDiscordLink('alliance',$this->notification->text['aggressorAllianceID'],UniverseName::firstOrNew(['entity_id' => $this->notification->text['aggressorAllianceID']],['category' => 'alliance', 'name' => trans('web::seat.unknown')])->name):$this->zKillBoardToDiscordLink('corporation',$this->notification->text['aggressorCorpID'],UniverseName::firstOrNew(['entity_id' => $this->notification->text['aggressorCorpID']],['category' => 'corporation', 'name' => trans('web::seat.unknown')] )->name);
            $region = Region::find($system->regionID)->name;
            $type = InvType::find($this->notification->text['typeID']);
            $planet = MapDenormalize::find($this->notification->text['moonID']);

            $sheildValue = floor($this->notification->text['shieldValue'] * 100);
            $armorValue = floor($this->notification->text['armorValue'] * 100);
            $hullValue = floor($this->notification->text['hullValue'] * 100);
            // converting values to percentages as currently they are onky 0-1
            $shieldPercent = number_format($sheildValue, 2);
            $armorPercent = number_format($armorValue, 2);
            $hullPercent = number_format($hullValue, 2);
            
            $embed->color(DiscordMessage::WARNING);
            $embed->author($corpName, 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
            $embed->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128');
            $embed->title("{$type->group->groupName} under attack");
            $embed->description("The {$type->typeName} at {$planet->name} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) is under attack by {$attacker}.\n**Shield:** {$shieldPercent}% | **Armor:** {$armorPercent}% | **Hull:** {$hullPercent}%");
            $embed->timestamp($this->notification->timestamp);
        });
    }
}