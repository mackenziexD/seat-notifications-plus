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
use Seat\Eveapi\Models\Sde\Planet;
use Seat\Eveapi\Models\Sde\Region;
use Carbon\Carbon;

/**
 * Class StructureUnderAttack.
 *
 */
class MoonMiningExtractionFinished extends AbstractNotification
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

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->attachment(function ($attachment) {
            $corpName = $this->notification->recipient->affiliation->corporation->name;
            $corpID = $this->notification->recipient->affiliation->corporation_id;
            $system = MapDenormalize::find($this->notification->text['solarSystemID']);
            $region = Region::find($system->regionID)->name;
            $type = InvType::find($this->notification->text['structureTypeID']);
            $planet = MapDenormalize::find($this->notification->text['moonID']);
            $structureName = $this->notification->text['structureName'];
            $autoFrac = $this->ldapToDateTime($this->notification->text['autoTime']);

            $description = "The extraction for **{$structureName}** at {$planet->itemName} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) is finished and the chunk is ready to be shot at. The chunk will automatically fracture on **{$autoFrac}**.\n\n";
            
            foreach($this->notification->text['oreVolumeByType'] as $typeID => $volume) {
                $type2 = InvType::find($typeID);
                $description .= "- {$type2->typeName}: ".number_format($volume, 2)." m³\n";
            }

            $attachment->author($corpName, '', 'https://images.evetech.net/corporations/'.$corpID.'/logo?size=128')
            ->title('Moon Mining Extraction Finished')
            ->thumb('https://images.evetech.net/types/'.$type->typeID.'/icon?size=128')
            ->content($description)
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