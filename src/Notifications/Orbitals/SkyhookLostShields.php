<?php

namespace Helious\SeatNotificationsPlus\Notifications\Orbitals;

use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Helious\SeatNotificationsPlus\Traits\EmbedNotificationTools;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Eveapi\Models\Sde\Region;

class SkyhookLostShields extends AbstractDiscordNotification
{
    use EmbedNotificationTools;

    private CharacterNotification $notification;

    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    public function populateMessage(DiscordMessage $message, $notifiable)
    {
        $message->from('Upwell Consortium', '');
        $message->embed(function (DiscordEmbed $embed) {
            $corpName = $this->notification->recipient->affiliation->corporation->name;
            $corpID = $this->notification->recipient->affiliation->corporation_id;
            $system = MapDenormalize::find($this->notification->text['solarsystemID']);
            $region = Region::find($system->regionID)->name;
            $type = InvType::find($this->notification->text['typeID']);
            $planet = MapDenormalize::find($this->notification->text['planetID']);
            $timeLeft = $this->ldap2DateTime($this->notification->text['timeLeft'], $this->notification->timestamp);

            $embed->color(DiscordMessage::ERROR);
            $embed->author($corpName, 'https://images.evetech.net/corporations/' . $corpID . '/logo?size=128');
            $embed->title('Skyhook Lost Shield');
            $embed->thumb('https://images.evetech.net/types/' . $type->typeID . '/icon?size=128');
            $embed->description("The {$type->typeName} at {$planet->name} in {$this->zKillBoardToDiscordLink('system',$system->itemID,$system->itemName)} ({$region}) has lost its shields. Armor timer end at: **{$timeLeft}**.");
            $embed->timestamp($this->notification->timestamp);
        });
  }
}