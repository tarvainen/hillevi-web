<?php

namespace App\Naming;

/**
 * Contains all the constants which has something to do with rights.
 *
 * @package App\Naming
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
abstract class Right
{
    /**
     * The right module constant values.
     */
    const ADMIN = 'admin';
    const DASHBOARD = 'dashboard';
    const USER_SETTINGS = 'user_settings';
}
