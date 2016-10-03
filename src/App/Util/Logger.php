<?php

namespace App\Util;

/**
 * A static logger. Finally!
 *
 * @package App\Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class Logger
{
    /**
     * Logs the message to the debug.log file in the logs folder.
     *
     * @param mixed $msg
     *
     * @return void
     */
    public static function log($msg)
    {
        file_put_contents(__DIR__ . '/../../../var/logs/debug.log', print_r($msg, true) . PHP_EOL, FILE_APPEND);
    }
}