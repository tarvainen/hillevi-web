<?php

namespace App\Controller;

use App\Util\FS;
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
     * Returns the vendor js as compressed.
     *
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

    /**
     * Returns the application common js files.
     *
     * @Route("app/js")
     * @Method("GET")
     *
     * @return Response
     */
    public function appJsAction()
    {
        $filename = $this->rootDir() . '/../web/js/app-files.js';

        if (FS::isFile($filename)) {
            return new Response(FS::readFile($filename));
        }

        return new Response('');
    }
}
