<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Base controller for all the other controllers.
 *
 * @package App\Controller
 *
 * @author  Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class CController extends Controller
{
    /**
     * Function to write debug in to the dev.log file.
     *
     * @param string $msg   The message to write in to the file.
     *
     * @return void
     */
    protected function log($msg = '')
    {
        $logger = $this->get('logger');
        $logger->debug($msg);
    }
}
