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
use Seat\Eveapi\Models\Sovereignty\SovereigntyMap;
use Seat\Eveapi\Models\Sde\Planet;
use Seat\Eveapi\Models\Sde\Region;
use Carbon\Carbon;

/**
 * Class EntosisCaptureStarted.
 *
 */
class EntosisCaptureStarted  extends AbstractNotification
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

            $alliance = SovereigntyMap::find($this->notification->text['solarSystemID'])->alliance;
            $allianceId = $alliance ? (int) $alliance->entity_id : null;
            $owner = $this->zKillBoardToDiscordLink('alliance', $allianceId, $alliance ? $alliance->name : '**Unknown**');
            
            $type = InvType::find($this->notification->text['structureTypeID']);
            
            $attachment->color('warning');
            $attachment->author($corpName, '', 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128');
            $attachment->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128');
            $attachment->title("{$type->group->groupName} in {$system->itemName} is being captured");
            $attachment->content("A capsuleer has started to influence the {$type->typeName} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) belonging to {$owner} with an Entosis Link.");
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