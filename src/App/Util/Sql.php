<?php

namespace App\Util;

/**
 * The utility class to handle different sql operations.
 *
 * @package App\Util
 *          
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class Sql
{
    /**
     * The constant values for the different types.
     */
    const TYPE_INT = 'int';
    const TYPE_STRING = 'string';
    const TYPE_DECIMAL = 'decimal';
    const TYPE_DATETIME = 'date';
    const TYPE_AUTO = 'auto';

    /**
     * Constants used in the actual sql queries.
     */
    const DB_TYPE_INT = 'INT(12)';
    const DB_TYPE_STRING = 'MEDIUMTEXT';
    const DB_TYPE_DECIMAL = 'DECIMAL(15, 2)';
    const DB_TYPE_DATETIME = 'DATETIME';
    const DB_TYPE_AUTO = 'INT(12) AUTO_INCREMENT PRIMARY KEY';

    /**
     * The database type mappings.
     *
     * @var array
     */
    private static $dbTypes = array(
        self::TYPE_INT      => self::DB_TYPE_INT,
        self::TYPE_STRING   => self::DB_TYPE_STRING,
        self::TYPE_DECIMAL  => self::DB_TYPE_DECIMAL,
        self::TYPE_DATETIME => self::DB_TYPE_DATETIME
    );

    /**
     * Forms a create sql for the single database table column.
     *
     * @param string     $title
     * @param string     $type
     *
     * @return null|string
     */
    public static function create($title, $type)
    {
        if (!array_key_exists($type, self::$dbTypes)) {
            return null;
        }

        return sprintf(
            '
                %1$s %2$s
            ',
            /** 1 */ $title,
            /** 2 */ self::$dbTypes[$type]
        );
    }
}
