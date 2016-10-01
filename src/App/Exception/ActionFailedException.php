<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Generic exception for 'action failed' with status code 500 for the REST.
 *
 * @package App\Exception
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class ActionFailedException extends HttpException
{
    /**
     * ActionFailedException constructor.
     *
     * @param   string  $action
     */
    public function __construct($action)
    {
        parent::__construct(500, sprintf(
            'Action %1$s failed.',
            /** 1 */ $action
        ));
    }
}
