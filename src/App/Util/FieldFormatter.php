<?php

namespace App\Util;

use App\Naming\FieldType;

/**
 * Utility class for formatting the output data from the interface.
 *
 * @package App\Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class FieldFormatter
{
    /**
     * Formats the data to the desired format.
     *
     * @param mixed $data
     * @param string $type
     *
     * @return float|int|null|string
     */
    public static function format($data, $type = FieldType::STRING)
    {
        switch ($type) {
            case FieldType::STRING:
                return '' . $data;
            case FieldType::INTEGER:
                return (int)$data;
            case FieldType::DECIMAL:
                return round((float)$data, 2);
            default:
                return null;
        }
    }

    /**
     * Returns the safely formatted text to be used as table name for example.
     *
     * @param string $val
     *
     * @return mixed
     */
    public static function toTableNameFormat($val)
    {
        return preg_replace('/[^A-Za-z_]/', '', $val);
    }
}
