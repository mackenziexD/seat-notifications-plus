<?php

namespace Helious\SeatNotificationsPlus\Observers;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Notifications\Models\NotificationGroup;
use Helious\SeatNotificationsPlus\Models\SeatNotificationsPlus;
use Seat\Notifications\Traits\NotificationDispatchTool;

class CharacterNotificationObserver
{
    use NotificationDispatchTool;

    public function created(CharacterNotification $notification)
    {
        \Log::error('Notification created');

        $corporationId = $notification->recipient->affiliation->corporation_id;
        // Attempt to retrieve the most recent notification for the corporation
        $mostRecentSeatNotification = SeatNotificationsPlus::where('corporation_id', $corporationId)
            ->latest('most_recent_notification')
            ->first();

        $isNewNotification = is_null($mostRecentSeatNotification) || $notification->timestamp > $mostRecentSeatNotification->most_recent_notification;

        // If there is no record or if the current notification is newer, update or create the record
        if ($isNewNotification) {
            SeatNotificationsPlus::updateOrCreate(
                ['corporation_id' => $corporationId],
                ['most_recent_notification' => $notification->timestamp]
            );
            $this->dispatch($notification);
        } else {
            \Log::error('Notification is not new, not dispatching');
            return;
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

        // Proceed with dispatching the notifications
        $this->dispatchNotifications($notification->type . ' [N+]', $groups, function ($notificationClass) use ($notification) {
            \Log::error($notificationClass);
            return (new $notificationClass($notification))->onQueue('high');
        });
    }
    
}