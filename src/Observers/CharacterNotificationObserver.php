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
        \Log::error('Notification created');

        $corporationId = $notification->recipient->affiliation->corporation_id;
        
        $mostRecentSeatNotification = SeatNotificationsPlus::where('corporation_id', $corporationId)
            ->latest('most_recent_notification')
            ->first();

        $isNewNotification = is_null($mostRecentSeatNotification) || $notification->timestamp > $mostRecentSeatNotification->most_recent_notification;

        // If there is no record or if the current notification is newer, update or create the record
        if (!$isNewNotification) return;
        SeatNotificationsPlus::updateOrCreate(
            ['corporation_id' => $corporationId],
            ['most_recent_notification' => $notification->timestamp]
        );
        $this->dispatch($notification);
    }

    /**
     * Queue notification based on notification kind.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    private function dispatch(CharacterNotification $notification)
    {
        // detect handlers setup for the current notification
        $handlers = config(sprintf('notifications.alerts.%s.handlers', $notification->type), []);

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
                    ->notify(new $handler($notification));
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