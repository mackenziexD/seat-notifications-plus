<?php

namespace Helious\SeatNotificationsPlus\Notifications\Sovereignties;

use Illuminate\Notifications\Messages\SlackMessage;
use Helious\SeatNotificationsPlus\Traits\attachmentNotificationTools;
use Helious\SeatNotificationsPlus\Notifications\AbstractNotification;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Eveapi\Models\Sde\Planet;
use Seat\Eveapi\Models\Sde\Region;
use Carbon\Carbon;

/**
 * Class EntosisCaptureStarted.
 *
 */
class SovStructureReinforced  extends AbstractNotification
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
            $region = Region::find($system->regionID)->name;
            $deloakTime = $this->ldapToDateTime($this->notification->text['decloakTime']);
            $campaignEventType = $this->campaignEventType($this->notification->text['campaignEventType']);
            $campaignEventTypeIcon = $this->campaignEventTypeIcon($this->notification->text['campaignEventType']);
            
            $attachment->color('danger')
            ->author($corpName, '', 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128')
            ->thumb($campaignEventTypeIcon)
            ->title("{$campaignEventType} in {$system->itemName} has entered reinforced mode")
            ->content("The **{$campaignEventType}** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) has been reinforced by hostile forces and command nodes will begin decloaking at **{$deloakTime}**")
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