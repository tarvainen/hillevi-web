<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Generic 'not found' exception with the status code 404 for the REST.
 *
 * @package App\Exception
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class NotFoundException extends HttpException
{
    /**
     * NotFoundException constructor.
     *
     * @param string  $objectName
     */
    public function __construct($objectName)
    {
        parent::__construct(404, sprintf(
            'Object %1$s was not found.',
            /** 1 */ $objectName
        ));
    }
}
