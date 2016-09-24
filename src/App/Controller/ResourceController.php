<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Controller for the resources.
 *
 * @package App\Controller
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class ResourceController extends CController
{
    /**
     * @Route("vendor/js")
     * @Method("GET")
     */
    public function vendorJsAction()
    {
        $filename = $this->rootDir() . '/../web/js/vendor.js';

        if (file_exists($filename)) {
            return new Response(file_get_contents($filename));
        }

        return new Response('');
    }
}