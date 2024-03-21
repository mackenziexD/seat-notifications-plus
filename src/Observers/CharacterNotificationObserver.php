<?php

namespace Helious\SeatNotificationsPlus\Observers;

use Illuminate\Support\Facades\Notification;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Notifications\Models\NotificationGroup;

use Helious\SeatNotificationsPlus\Models\SeatNotificationsPlus;

class CharacterNotificationObserver
{
    public function created(CharacterNotification $notification)
    {
        \Log::info('Notification created'); // Consider using \Log::info or \Log::debug for non-error logs.
    
        $corporationId = $notification->recipient->affiliation->corporation_id;
    
        // Check if the notification ID already exists
        $exists = SeatNotificationsPlus::where('corporation_id', $corporationId)
            ->where('notification_id', $notification->notification_id)
            ->exists();
    
        // Determine if the notification is less than 10 minutes old from now
        $isRecent = now()->diffInMinutes($notification->timestamp) < 10;
    
        // If the notification doesn't exist and it's recent, create and dispatch it
        if (!$exists && $isRecent) {
            
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
        // detect handlers setup for the current notification
        $handlers = config(sprintf('notifications.alerts.%s.handlers', $notification->type  . ' [N+]'), []);

        // if the notification is unsupported (no handlers available), log and interrupt
        if (empty($handlers)) {
            logger()->debug('Unsupported notification type.', [
                'type' => $notification->type,
                'sender_type' => $notification->sender_type,
                'notification' => $notification->text,
            ]);

            return;
        }

        // retrieve routing candidates for the current notification
        $routes = $this->getRoutingCandidates($notification);

        // in case no routing candidates has been delivered, exit
        if ($routes->isEmpty())
            return;

        // attempt to enqueue a notification for each routing candidate
        $routes->each(function ($integration) use ($handlers, $notification) {
            if (array_key_exists($integration->channel, $handlers)) {

                // extract handler from the list
                $handler = $handlers[$integration->channel];

                // enqueue the notification
                Notification::route($integration->channel, $integration->route)
                    ->notifyNow(new $handler($notification));
            }
        });
    }

    /**
     * Provide a unique list of notification channels (including driver and route).
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     * @return \Illuminate\Support\Collection
     */
    private function getRoutingCandidates(CharacterNotification $notification)
    {
        $settings = NotificationGroup::with('alerts', 'affiliations')
            ->whereHas('alerts', function ($query) use ($notification) {
                $query->where('alert', $notification->type . ' [N+]');
            })->whereHas('affiliations', function ($query) use ($notification) {
                $query->where('affiliation_id', $notification->character_id);
                $query->orWhere('affiliation_id', $notification->recipient->affiliation->corporation_id);
            })->get();

        // loop over each group candidate and collect available integrations
        $routes = $settings->map(function ($group) {
            return $group->integrations->map(function ($channel) {

                // extract the route value from settings field
                $setting = (array) $channel->settings;
                $key = array_key_first($setting);
                $route = $setting[$key];

                // build a composite object build with channel and route
                return (object) [
                    'channel' => $channel->type,
                    'route' => $route,
                ];
            });
        });

        return $routes->flatten()->unique(function ($integration) {
            return $integration->channel . $integration->route;
        });
    }
    
}