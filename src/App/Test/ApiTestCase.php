<?php

namespace App\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Base class for api tests.
 *
 * @package App\Test
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class ApiTestCase extends WebTestCase
{
    /**
     * Returns the new instance of the ApiRequest class.
     *
     * @param   string $route
     *
     * @return  ApiRequest
     */
    public function route($route)
    {
        return ApiRequest::instance(static::createClient())
            ->route($route);
    }
}