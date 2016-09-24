<?php

namespace App\Controller;

use Namshi\JOSE\SimpleJWS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Base controller for all the other controllers.
 *
 * @package App\Controller
 *
 * @author  Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class CController extends Controller
{
    const TEMPLATE_DIR = '/client/templates/';

    /**
     * The container for the current user.
     *
     * @var null
     */
    protected $user = null;

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

    /**
     * Returns the root directory.
     *
     * @return mixed
     */
    protected function rootDir()
    {
        return $this->get('kernel')->getRootDir();
    }

    /**
     * Returns the current user. If the user is not authenticated, the null will be returned.
     *
     * @return array|null
     */
    protected function getUser()
    {
        if (is_null($this->user)) {
            if (isset($_COOKIE['authorization'])) {
                $jws = SimpleJWS::load($_COOKIE['authorization']);
                $key = openssl_pkey_get_public('file://' . $this->get('kernel')->getRootDir() . '/var/jwt/public.pem');

                if ($jws->isValid($key, 'RS256')) {
                    $this->user = $jws->getPayload();
                }
            }
        }

        return $this->user;
    }

    /**
     * Does a simple page render without any template engines.
     *
     * @param string $module
     * @param string $file
     * @param string $extension
     *
     * @return Response
     */
    protected function renderHTML($module, $file, $extension = 'html')
    {
        $templateFile = sprintf(
            '%1$s/../%2$s%3$s/%4$s.%5$s',
            /** 1 */ $this->rootDir(),
            /** 2 */ self::TEMPLATE_DIR,
            /** 3 */ $module,
            /** 4 */ $file,
            /** 5 */ $extension
        );

        if (file_exists($templateFile)) {
            $html = file_get_contents($templateFile);
        } else {
            $html = '';
        }

        return new Response($html);
    }
}
