<?php

namespace Helious\SeatNotificationsPlus\Notifications\Sovereignties;

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
use Seat\Eveapi\Models\Sde\Constellation;
use Carbon\Carbon;

/**
 * Class EntosisCaptureStarted.
 *
 */
class SovCommandNodeEventStarted  extends AbstractNotification
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
            $const = MapDenormalize::find($this->notification->text['constellationID']);
            $region = Region::find($system->regionID)->name;
            
            $attachment->color('warning');
            $attachment->author($corpName, '', 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
            if($this->notification->text['campaignEventType'] === 1){
                // TCU Event
                $attachment->thumb('https://images.evetech.net/types/32226/icon?size=128');
                $attachment->title("Command nodes for Territorial Claim Unit in {$system->itemName} have begun to decloak");
                $attachment->content("Command nodes for **Territorial Claim Unit** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) can now be found throughout the {$const->name} constellation");
            } elseif ($this->notification->text['campaignEventType'] === 2){
                // IHUB Event
                $attachment->thumb('https://images.evetech.net/types/32458/icon?size=128');
                $attachment->title("Command nodes for Infrastructure Hub in {$system->itemName} have begun to decloak");
                $attachment->content("Command nodes for **Infrastructure Hub** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) can now be found throughout the {$const->name} constellation");
            } else {
                // Unknown Event
                $attachment->thumb('https://images.evetech.net/types/0/icon?size=128');
                $attachment->title("Command nodes for Unknown Structure in {$system->itemName} have begun to decloak");
                $attachment->content("Command nodes for an **Unknown Structure** in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) can now be found throughout the {$const->name} constellation");
            }
            $attachment->timestamp(Carbon::createFromFormat('Y-m-d H:i:s', $this->notification->timestamp));
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