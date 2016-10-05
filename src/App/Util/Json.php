<?php

namespace App\Util;

/**
 * Wrapper class for the JSON operations.
 *
 * @package App\Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class Json
{
    /**
     * Wrapper for the json_encode
     *
     * @param mixed $data
     *
     * @return string
     */
    public static function encode($data)
    {
        return json_encode($data);
    }

    /**
     * Wrapper for the json_decode. Returns the result as associative array.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public static function decode($data)
    {
        return json_decode($data, true);
    }
}
