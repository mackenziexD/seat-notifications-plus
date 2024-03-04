<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Helious\SeatNotificationsPlus\Traits;

use Seat\Services\Exceptions\EveImageException;
use Seat\Services\Image\Eve;

/**
 * Trait NotificationTools.
 *
 * @package Seat\Notifications\Traits
 */
trait NotificationTools
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
    public function ldapToDateTime(int $ldap): string
    {
        $baseTimestamp = time(); // Current Unix timestamp
        $timeLeftInSeconds = $ldap / 10000000;
        $Timestamp = $baseTimestamp + $timeLeftInSeconds;
        $DateTime = gmdate("Y-m-d H:i:s", $Timestamp);
        return $DateTime;
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