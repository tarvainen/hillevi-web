<?php

namespace App\Util;

/**
 * Utility class for password handling.
 *
 * @package App\Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class Password
{
    /**
     * Hashes the plain password.
     *
     * @param string $plain
     *
     * @return bool|string
     */
    public static function hash($plain)
    {
        return password_hash($plain, PASSWORD_BCRYPT);
    }

    /**
     * Tests if the hashed password matches with the plain.
     *
     * @param string    $plain
     * @param string    $hash
     *
     * @return bool
     */
    public static function test($plain, $hash)
    {
        return password_verify($plain, $hash);
    }
}
