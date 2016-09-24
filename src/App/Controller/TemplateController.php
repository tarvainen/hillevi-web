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
    const TEMPLATE_DIR = '/client/templates/';

    /**
     * Fetches the template from the file. This does not use the Twig template engine, it just
     * reads the HTML file and pukes it out.
     *
     * @Route("template/{template}")
     * @Method("GET")
     *
     * @return Response
     */
    public function actionGetTemplate($template)
    {
        $templateFile = $this->rootDir() . '/../' .self::TEMPLATE_DIR . $template . '.html';

        if (file_exists($templateFile)) {
            $html = file_get_contents($templateFile);
        } else {
            $html = '';
        }

        return new Response($html);
    }
}
