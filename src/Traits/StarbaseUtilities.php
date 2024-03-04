<?php

namespace Helious\SeatNotificationsPlus\Traits;

trait StarbaseUtilities 
{

    public static function starbaseSize($typeName) {
        if (strpos(strtolower($typeName), 'medium') !== false) {
            return 'Medium';
        } elseif (strpos(strtolower($typeName), 'small') !== false) {
            return 'Small';
        }
        // If 'Medium' or 'Small' is not found in the type name, it's considered 'Large'
        return 'Large';
    }

    public static function fuelPerHour($typeName) {
        $size = self::starbaseSize($typeName);
        switch ($size) {
            case 'Large':
                return 40;
            case 'Medium':
                return 20;
            case 'Small':
                return 10;
        }
        return null;
    }

    public static function fuelDuration($typeName, $fuelQuantity, $hasSov = false) {
        $sovDiscount = $hasSov ? 0.25 : 0;
        $amountPerHour = self::fuelPerHour($typeName);
        if ($amountPerHour === null) {
            throw new \Exception("Can only calculate fuel durations for starbases");
        }
        $seconds = floor(3600 * $fuelQuantity / ($amountPerHour * (1 - $sovDiscount)));
        return $seconds;
    }
}
