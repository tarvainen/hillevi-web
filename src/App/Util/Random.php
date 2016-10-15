<?php

namespace App\Util;

/**
 * Utility class for creating some random things.
 *
 * @package App\Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class Random
{
    /**
     * Creates a random integer value.
     *
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    public static function integer($min = 0, $max = 100)
    {
        return rand($min, $max);
    }

    /**
     * Creates a random decimal value.
     *
     * @param int $min
     * @param int $max
     * @param int $decimals
     *
     * @return float|int
     */
    public static function decimal($min = 0, $max = 100, $decimals = 2)
    {
        $multiplier = $decimals * 10;

        $random = self::integer($min * $multiplier, $max * $multiplier);

        return $random / $multiplier;
    }
}
