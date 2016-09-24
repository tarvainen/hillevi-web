<?php

namespace App\Util;

/**
 * Utility class for different file system operations.
 *
 * @package App\FS
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class FS
{
    /**
     * Reads the file contents and returns it.
     *
     * @param   string    $filename
     *
     * @return  string
     */
    public static function readFile($filename)
    {
        if (self::isFile($filename)) {
            return file_get_contents($filename);
        }

        return '';
    }

    /**
     * Checks whether the file exists or not.
     *
     * @param   string  $filename
     *
     * @return  bool
     */
    public static function isFile($filename)
    {
        return file_exists($filename);
    }
}