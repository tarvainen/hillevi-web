<?php

namespace App\Controller;

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
}
