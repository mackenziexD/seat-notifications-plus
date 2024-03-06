<?php

namespace Helious\SeatNotificationsPlus\Notifications\Structures;

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
use Carbon\Carbon;

/**
 * Class StructureUnderAttack.
 *
 */
class OrbitalAttacked extends AbstractNotification
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
            $attacker = !empty($this->notification->text['aggressorAllianceID']) 
            ? $this->zKillBoardToDiscordLink('alliance',$this->notification->text['aggressorAllianceID'],UniverseName::firstOrNew(['entity_id' => $this->notification->text['aggressorAllianceID']],['category' => 'alliance', 'name' => trans('web::seat.unknown')])->name)
            : $this->zKillBoardToDiscordLink('corporation',$this->notification->text['aggressorCorpID'],UniverseName::firstOrNew(['entity_id' => $this->notification->text['aggressorCorpID']],['category' => 'corporation', 'name' => trans('web::seat.unknown')])->name);
            $system = MapDenormalize::find($this->notification->text['solarSystemID']);
            $region = Region::find($system->regionID)->name;
            $type = InvType::find($this->notification->text['typeID']);
            $planet = MapDenormalize::find($this->notification->text['planetID']);
            
            $attachment->color('danger')
            ->author($corpName, '', 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128')
            ->title('Orbital under attack')
            ->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128')
            ->content("The {$type->typeName} at {$planet->name} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) is under attack by {$attacker}.")
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