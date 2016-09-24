<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

/**
 * The AppController to handle all the client requests for the UI.
 *
 * @package App\Controller
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class AppController extends CController
{
    /**
     * The function to handle the index request.
     *
     * @Route("")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->renderHTML('index', 'index');
    }
}