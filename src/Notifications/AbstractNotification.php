<?php

namespace Helious\SeatNotificationsPlus\Notifications;

use Seat\Notifications\Jobs\AbstractNotificationJob;

abstract class AbstractNotification extends AbstractNotificationJob
{
    /**
     * {@inheritdoc}
     */
    public $queue = 'high';

    abstract public function via($notifiable);
}