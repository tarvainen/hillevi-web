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
    const TYPE_DATETIME = 'datetime';
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
     * Constants for date formats.
     */
    const DATE_FORMAT_HOUR = '%d.%m %k';
    const DATE_FORMAT_DAY = '%d.%m';
    const DATE_FORMAT_WEEK = '%x/%v';
    const DATE_FORMAT_MONTH = '%m/%Y';
    const DATE_FORMAT_YEAR = '%Y';

    /**
     * The database type mappings.
     *
     * @var array
     */
    public static $dbTypes = array(
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
            /** 1 */ FieldFormatter::toTableNameFormat($title),
            /** 2 */ self::$dbTypes[$type]
        );
    }

    /**
     * Creates a simple insert statement from parameters.
     *
     * @param array  $data
     * @param string $table
     * @param array  $columns
     * @param int    $index
     *
     * @return array
     */
    public static function insert(array $data, $table, $columns, $index = 0)
    {
        $values = [];
        $bindings = [];

        foreach ($columns as $key => $value) {
            if (array_key_exists($value['field'], $data)) {
                $values[$value['field']] = FieldFormatter::format($data[$value['field']], $value['type']);
            }
        }

        foreach ($values as $key => $value) {
            $bindings[':' . $key . $index] = $value;
        }

        $sql = sprintf(
            'INSERT INTO %1$s (%2$s) VALUES(%3$s);',
            /** 1 */ $table,
            /** 2 */ implode(',', array_keys($values)),
            /** 3 */ implode(',', array_keys($bindings))
        );

        return [$sql, $bindings];
    }
}
