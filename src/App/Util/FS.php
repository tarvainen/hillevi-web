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
     * Reads all files from the directory.
     *
     * @param   string  $path
     * @return  string
     */
    public static function readAllFiles($path)
    {
        if (self::isDir($path)) {
            $files = scandir($path);

            $result = '';

            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $result .= self::readFile($path . DIRECTORY_SEPARATOR . $file);
                }
            }

            return $result;
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

    /**
     * Checks whether the directory is a directory.
     *
     * @param   string  $dir
     * @return  bool
     */
    public static function isDir($dir)
    {
        return is_dir($dir);
    }
}
