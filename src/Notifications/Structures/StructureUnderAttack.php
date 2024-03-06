<?php

namespace Helious\SeatNotificationsPlus\Notifications\Structures;

use Illuminate\Notifications\Messages\SlackMessage;
use Helious\SeatNotificationsPlus\Traits\attachmentNotificationTools;
use Helious\SeatNotificationsPlus\Notifications\AbstractNotification;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Eveapi\Models\Sde\Region;
use Carbon\Carbon;

/**
 * Class StructureUnderAttack.
 *
 */
class StructureUnderAttack  extends AbstractNotification
{
    use attachmentNotificationTools;

    private CharacterNotification $notification;

    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->attachment(function ($attachment) {
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
            
            $attachment->color('danger')
            ->author($corpName, '', 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128')
            ->title('Structure Under Attack')
            ->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128')
            ->content("The {$type->typeName} **{$structureName}** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) is under attack by **{$attacker}**.\n**Shield:** {$sheild}% | **Armor:** {$armor}% | **Hull:** {$hull}%")
            ->timestamp(Carbon::createFromFormat('Y-m-d H:i:s', $this->notification->timestamp));
        });
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->notification->text;
    }
}