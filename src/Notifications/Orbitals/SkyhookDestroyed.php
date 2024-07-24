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
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Eveapi\Models\Sde\Planet;
use Seat\Eveapi\Models\Sde\Region;

/**
 * Class SkyhookDestroyed.
 *
 */
class SkyhookDestroyed extends AbstractDiscordNotification
{

  use EmbedNotificationTools;

  private CharacterNotification $notification;

  public function __construct(CharacterNotification $notification)
  {
      $this->notification = $notification;
  }

  public function populateMessage(DiscordMessage $message, $notifiable)
  {
  }

}