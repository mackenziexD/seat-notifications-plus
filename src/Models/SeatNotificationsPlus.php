<?php

namespace Helious\SeatNotificationsPlus\Models;

use Illuminate\Database\Eloquent\Model;

class SeatNotificationsPlus extends Model
{
    protected $table = 'seat_notifications_plus_notifcations';

    protected $fillable = [
        'corporation_id',
        'notification_id',
        'timestamp'
    ];

    public function corporation()
    {
        return $this->belongsTo('Seat\Eveapi\Models\Corporation\CorporationInfo', 'corporation_id', 'corporation_id');
    }

    public $timestamps = true;
}