<?php

namespace App\Util;

/**
 * Utility class for array handling.
 *
 * @package App\Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class Arrays
{
    /**
     * Check if the given value is an multidimensional array.
     *
     * @param  mixed $array
     *
     * @return bool
     */
    public static function isMultidimensionalArray($array)
    {
        if (!is_array($array)) {
            return false;
        }

        foreach ($array as $item) {
            if (is_array($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Map values by key out of the array.
     *
     * @param array  $array
     * @param string $key
     *
     * @return array
     */
    public static function map(array $array, $key)
    {
        if (!$key || empty($array)) {
            return [];
        }

        $result = [];

        foreach ($array as $item) {
            if (isset($item[$key])) {
                $result[] = $item[$key];
            }
        }

        return $result;
    }
}
