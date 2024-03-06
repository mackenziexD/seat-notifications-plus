<?php

namespace Helious\SeatNotificationsPlus\Notifications\Structures;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Notifications\Notifications\AbstractNotification;
use Helious\SeatNotificationsPlus\Traits\attachmentNotificationTools;
use Helious\SeatNotificationsPlus\Traits\StarbaseUtilities;

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
class AllAnchoringMsg extends AbstractNotification
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
            $owner = !empty($this->notification->text['allianceID']) ? $this->zKillBoardToDiscordLink('alliance',$this->notification->text['allianceID'],UniverseName::firstOrNew(['entity_id' => $this->notification->text['allianceID']],['category' => 'alliance', 'name' => trans('web::seat.unknown')])->name):$this->zKillBoardToDiscordLink('corporation',$this->notification->text['corpID'],UniverseName::firstOrNew(['entity_id' => $this->notification->text['corpID']],['category' => 'corporation', 'name' => trans('web::seat.unknown')] )->name);
            $system = MapDenormalize::find($this->notification->text['solarSystemID']);
            $region = Region::find($system->regionID)->name;
            $type = InvType::find($this->notification->text['typeID']);
            $moon = MapDenormalize::find($this->notification->text['moonID']);
            
            $attachment->color('warning')
            ->author($corpName, '', 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128')
            ->title("{$type->group->groupName} anchored in {$system->itemName}")
            ->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128')
            ->content("a {$type->typeName} from {$owner} has anchored in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) near **{$moon->itemName}**.")
            ->timestamp(Carbon::createFromFormat('Y-m-d H:i:s', $this->notification->timestamp)->getTimestamp());
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