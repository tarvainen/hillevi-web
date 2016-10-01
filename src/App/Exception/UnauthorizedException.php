<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Generic 'unauthorized' exception with the status code 401 for the REST.
 *
 * @package App\Exception
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class UnauthorizedException extends HttpException
{
    /**
     * UnauthorizedException constructor.
     */
    public function __construct()
    {
        parent::__construct(401, 'Unauthorized');
    }
}
