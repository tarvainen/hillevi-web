<?php

namespace App\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * The annotation driver to handle all the custom annotations.
 *
 * @package App\Annotation
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class AnnotationDriver
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var Request
     */
    private $request;

    /**
     * AnnotationDriver constructor.
     *
     * @param AnnotationReader $reader
     * @param Container $container
     * @param RequestStack $requestStack
     */
    public function __construct($reader, $container, $requestStack)
    {
        $this->reader = $reader;
        $this->container = $container;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * The function to handle the event.
     *
     * @param FilterControllerEvent $event
     *
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $object = new \ReflectionObject($controller[0]);
        $method = $object->getMethod($controller[1]);

        foreach ($this->reader->getMethodAnnotations($method) as $configuration) {
            if (method_exists($configuration, 'validate')) { // we found our custom annotation
                /**
                 * @var ContainerAwareAnnotation $configuration
                 */
                $configuration->setContainer($this->container);
                $configuration->setController($controller[0]);
                $configuration->validate($this->request);
            }
        }
    }
}
