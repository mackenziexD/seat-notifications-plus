<?php

namespace Helious\SeatNotificationsPlus\Traits;

use Seat\Services\Exceptions\EveImageException;
use Seat\Services\Image\Eve;

/**
 * Trait NotificationTools.
 *
 * @package Seat\Notifications\Traits
 */
trait EmbedNotificationTools
{
    /**
     * Build a link to Eve Type.
     *
     * @param  int  $type_id
     * @return string
     */
    public function typeIconUrl(int $type_id): string
    {
        try {
            $icon = new Eve('types', 'icon', $type_id, 64, [], false);
        } catch (EveImageException $e) {
            logger()->error($e->getMessage());
            report($e);

            return '';
        }

        return sprintf('https:%s', $icon->url(64));
    }

    /**
     * LDAP to readable date/time format
     * @param  int  $ldap
     */
    public function ldap2DateTime($ldap, $baseTimestampStr)
    {
        $timeDeltaInSeconds = $ldapTimeDelta / 10000000;
        $baseTimestamp = strtotime($baseTimestampStr);
        $eventTimestamp = $baseTimestamp + $timeDeltaInSeconds;
        $eventDateTime = gmdate("Y-m-d H:i:s", $eventTimestamp);
        return $eventDateTime;
    }

    public function ldapToDateTime($ldap)
    {
        $secondsFrom1601To1970 = (1970 - 1601) * 365 * 24 * 60 * 60;
        $leapYears = 89; // Number of leap years between 1601 and 1970
        $secondsFrom1601To1970 += $leapYears * 24 * 60 * 60; // Add the leap seconds
        
        $unixTimestamp = ($ldap / 10000000) - $secondsFrom1601To1970;
            
            // Convert Unix timestamp to human-readable format
        return gmdate("Y-m-d H:i:s", $unixTimestamp);
    }

    /**
     * Build a link to zKillboard using Discord message formatting.
     *
     * @param  string  $type
     * @param  int  $id
     * @param  string  $name
     * @return string
     */
    public function zKillBoardToDiscordLink(string $type, int $id, string $name): string
    {
        if (! in_array($type, ['ship', 'character', 'corporation', 'alliance', 'kill', 'system', 'location']))
            return '';

        return sprintf('[%s](https://zkillboard.com/%s/%d/)', $name, $type, $id);
    }

    /**
     * Convert a campaign event enum type into an Type Name.
     *
     * @param  int  $type
     * @return string
     */
    public function campaignEventType(int $type): string
    {
        switch ($type) {
            case 1:
                return 'Territorial Claim Unit';
            case 2:
                return 'Infrastructure Hub';
            case 3:
                return 'Outpost';
            default:
                return 'Unknown';
        }
    }
}