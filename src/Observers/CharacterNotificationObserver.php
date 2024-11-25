<?php

namespace Helious\SeatNotificationsPlus\Observers;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Notifications\Models\NotificationGroup;
use Helious\SeatNotificationsPlus\Models\SeatNotificationsPlus;
use Seat\Notifications\Traits\NotificationDispatchTool;

class CharacterNotificationObserver
{
    use NotificationDispatchTool;
    
    const EXPIRATION_DELAY = 3600;

    public function created(CharacterNotification $notification)
    {
        $corporationId = $notification->recipient->affiliation->corporation_id;
    
        $exists = SeatNotificationsPlus::where('corporation_id', $corporationId)
            ->where('notification_id', $notification->notification_id)
            ->exists();
    
        // ignore any notification created since more than 60 minutes
        if (carbon()->diffInSeconds($notification->timestamp) > self::EXPIRATION_DELAY)
            return;
    
        if (!$exists) {
            
            SeatNotificationsPlus::create([
                'corporation_id' => $corporationId,
                'notification_id' => $notification->notification_id, 
                'timestamp' => $notification->timestamp
            ]);

            $this->dispatch($notification);
        }
    }

    /**
     * Queue notification based on notification kind.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    private function dispatch(CharacterNotification $notification)
    {
        
        

        $groups = NotificationGroup::with('alerts', 'affiliations')
            ->whereHas('alerts', function ($query) use ($notification) {
                $query->where('alert', $notification->type . ' [N+]');
            })->whereHas('affiliations', function ($query) use ($notification) {
                $query->where('affiliation_id', $notification->character_id);
                $query->orWhere('affiliation_id', $notification->recipient->affiliation->corporation_id);
            })->get();

        $this->dispatchNotifications($notification->type . ' [N+]', $groups, function ($notificationClass) use ($notification) {
            return (new $notificationClass($notification))->onQueue('high');
        });

        \Log::error($notification->type . " [N+] is not part of any notifcation groups.");
    }
    
}