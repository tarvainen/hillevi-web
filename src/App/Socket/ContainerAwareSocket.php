<?php

namespace App\Socket;
use Symfony\Component\DependencyInjection\Container;

/**
 * A container aware socket for getting access to the database also.
 *
 * @package App\Socket
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class ContainerAwareSocket
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * ContainerAwareSocket constructor.
     *
     * @param Container $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }
}