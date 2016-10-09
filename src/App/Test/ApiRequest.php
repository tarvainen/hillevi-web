<?php

namespace App\Test;

use Symfony\Bundle\FrameworkBundle\Client;

/**
 * A api request object for making test request a lot simpler to create in the tests.
 *
 * @package App\Test
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class ApiRequest
{
    /**
     * The used method.
     *
     * @var string
     */
    private $_method = 'POST';

    /**
     * The used route.
     *
     * @var
     */
    private $_route;

    /**
     * The headers passed.
     *
     * @var array
     */
    private $_headers = [];

    /**
     * The parameters passed.
     *
     * @var array
     */
    private $_parameters = [];

    /**
     * The client object.
     *
     * @var null|Client
     */
    private $client = null;

    /**
     * ApiRequest constructor.
     *
     * @param Client $client
     */
    public function __construct($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Set the method
     *
     * @param string $method
     *
     * @return ApiRequest
     */
    public function method($method)
    {
        $this->_method = $method;

        return $this;
    }

    /**
     * Set the route
     *
     * @param string $route
     *
     * @return ApiRequest
     */
    public function route($route)
    {
        $this->_route = $route;

        return $this;
    }

    /**
     * Set parameters
     *
     * @param array $parameters
     *
     * @return ApiRequest
     */
    public function parameters($parameters)
    {
        $this->_parameters = $parameters;

        return $this;
    }

    /**
     * Set headers
     *
     * @param array $headers
     *
     * @return ApiRequest
     */
    public function headers($headers)
    {
        $this->_headers = $headers;

        return $this;
    }

    /**
     * Execute the route
     *
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function execute()
    {
        $this->client->request(
            $this->_method,
            $this->_route,
            $this->_parameters,
            [],
            $this->_headers
        );

        return $this->client->getResponse();
    }

    /**
     * Returns an instance of the ApiRequest
     *
     * @param   Client $client
     *
     * @return  ApiRequest
     */
    public static function instance($client)
    {
        return new ApiRequest($client);
    }
}