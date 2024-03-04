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
class AllAnchoringMsg extends AbstractDiscordNotification
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
            $owner = !empty($textArray['allianceID']) ? $this->zKillBoardToDiscordLink('alliance',$this->notification->text['allianceID'],UniverseName::firstOrNew(['entity_id' => $this->notification->text['allianceID']],['category' => 'alliance', 'name' => trans('web::seat.unknown')])->name):$this->zKillBoardToDiscordLink('corporation',$this->notification->text['corpID'],UniverseName::firstOrNew(['entity_id' => $this->notification->text['corpID']],['category' => 'corporation', 'name' => trans('web::seat.unknown')] )->name);
            $corpID = $this->notification->recipient->corporation->corporation_id;
            $system = MapDenormalize::find($this->notification->text['solarsystemID']);
            $region = MapDenormalize::find($system->regionID)->region->name;
            $type = InvType::find($this->notification->text['typeID']);
            $moon = MapDenormalize::find($this->notification->text['moonID']);
            
            $embed->color(DiscordMessage::WARNING);
            $embed->author('{$corpName}', 'https://images.evetech.net/corporations/{$corpID}/logo?size=128');
            $embed->title('{$type->group->groupName} anchored in ${$system->itemName}');
            $embed->thumb('https://images.evetech.net/types/{$type->typeID}/icon?size=128');
            $embed->description("a {$type->typeName} from ${$owner} has anchored in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) near **{$moon}**.");
            $embed->timestamp($this->notification->timestamp);
        });
    }
}