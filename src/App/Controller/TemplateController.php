<?php

namespace App\Controller;

use App\Util\FS;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for the template handling. We write templates in plain HTML and Angular, no other engines used.
 *
 * @package App\Controller
 *
 * @author Atte Tarvainen   <atte.tarvainen@pp1.inet.fi>
 */
class TemplateController extends CController
{
    /**
     * Fetches the template from the file. This does not use the Twig template engine, it just
     * reads the HTML file and pukes it out.
     *
     * @Route("template/{module}/{template}")
     * @Method("GET")
     *
     * @param string    $module     The module where the file is fetched.
     * @param string    $template   The template's filename.
     *
     * @return Response
     */
    public function actionGetTemplate($module, $template)
    {
        return $this->renderHTML($module, $template);
    }

    /**
     * Fetches the angular files for the module.
     *
     * @Route("ng/{module}")
     * @Method("GET")
     *
     * @param string    $module     The module where the file is fetched.
     *
     * @return Response
     */
    public function ngAction($module)
    {
        return new Response(FS::readAllFiles($this->rootDir() . '/../app/Resources/js/' . $module));
    }

    /**
     * Fetches the css files for the module.
     *
     * @Route("css/{module}")
     * @Method("GET")
     *
     * @param string    $module     The module where the file is fetched.
     *
     * @return Response
     */
    public function cssAction($module)
    {
        return new Response(FS::readAllFiles($this->rootDir() . '/../app/Resources/css/' . $module));
    }
}
