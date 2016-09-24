<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for different cache actions. The manifest file is served here.
 *
 * @package App\Controller
 */
class CacheController extends CController
{
    /**
     * @Route("cache/manifest")
     */
    public function manifestAction()
    {
        return new Response($this->readFile('hillevi.appcache'));
    }
}
