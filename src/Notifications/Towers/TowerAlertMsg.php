<?php

namespace Helious\SeatNotificationsPlus\Notifications\Towers;

use Illuminate\Notifications\Messages\SlackMessage;
use Helious\SeatNotificationsPlus\Traits\attachmentNotificationTools;
use Seat\Notifications\Notifications\AbstractNotification;

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
class TowerAlertMsg extends AbstractNotification
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
                
                $attachment->color('warning');
                $attachment->author($corpName, 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
                $attachment->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128');
                $attachment->title("{$type->group->groupName} under attack");
                $attachment->description("The {$type->typeName} at {$planet->name} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) is under attack by {$attacker}.\n**Shield:** {$shieldPercent}% | **Armor:** {$armorPercent}% | **Hull:** {$hullPercent}%");
                $attachment->timestamp($this->notification->timestamp);
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