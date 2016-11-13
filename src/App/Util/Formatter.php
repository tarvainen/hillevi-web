<?php

namespace App\Util;

/**
 * Utility class for different formatting stuff.
 *
 * @package App\Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class Formatter
{
    /**
     * Converts the value to padded format. For example Formatter::toPadded(2, 5) -> '00002'.
     *
     * @param mixed $value
     * @param int   $length
     *
     * @return string
     */
    public static function toPadded($value, $length = 2)
    {
        $value = !is_null($value) ? $value . '' : '';
        $length = is_null($length) ? 2 : (int)$length;

        for ($i = 0, $val = strlen($value); $i < $length - $val; $i++) {
            $value = '0' . $value;
        }

        return $value;
    }
}