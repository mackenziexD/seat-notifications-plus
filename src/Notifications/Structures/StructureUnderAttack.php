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
use Seat\Eveapi\Models\Sde\Region;

/**
 * Class StructureUnderAttack.
 *
 */
class StructureUnderAttack extends AbstractDiscordNotification
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
            $corpID = $this->notification->recipient->affiliation->corporation_id;;
            $attacker = !empty($this->notification->text['allianceID']) ? $this->zKillBoardToDiscordLink('alliance',$this->notification->text['allianceID'],$this->notification->text['allianceName']) : $this->zKillBoardToDiscordLink('corporation',$this->notification->text['corpLinkData'][2],$this->notification->text['corpName']);
            $system = MapDenormalize::find($this->notification->text['solarsystemID']);
            $region = Region::find($system->regionID)->name;
            $structureData = UniverseStructure::find($this->notification->text['structureID']);
            $structureName = $structureData ? $structureData->name : 'Unknown Structure';
            $type = InvType::find($this->notification->text['structureShowInfoData'][1]);
            $sheild = number_format($this->notification->text['shieldPercentage'], 2);
            $armor = number_format($this->notification->text['armorPercentage'], 2);
            $hull = number_format($this->notification->text['hullPercentage'], 2);
            
            $embed->color('danger');
            $embed->author($corpName, 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
            $embed->title('Structure Under Attack');
            $embed->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128');
            $embed->description("The {$type->typeName} **{$structureName}** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) is under attack by **{$attacker}**.\n**Shield:** {$sheild}% | **Armor:** {$armor}% | **Hull:** {$hull}%");
            $embed->timestamp($this->notification->timestamp);
        });
    }
}