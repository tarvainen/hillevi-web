<?php

namespace App\Annotation;

use App\Controller\CController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Container and controller aware annotation for making more deep annotation handlings within the controller's data.
 *
 * @package App\Annotation
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
abstract class ContainerAwareAnnotation extends AbstractAnnotation implements ContainerAwareInterface
{
    /**
     * @var null|Container
     */
    protected $container = null;

    /**
     * @var null|CController
     */
    protected $controller = null;

    /**
     * Injects the container for this annotation.
     *
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Injects the controller for this annotation.
     *
     * @param CController $controller
     */
    public function setController(CController $controller)
    {
        $this->controller = $controller;
    }
}
