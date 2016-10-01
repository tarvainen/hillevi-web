<?php

namespace App\Controller;

use App\Entity\User;
use App\Util\FS;
use JMS\Serializer\SerializerBuilder;
use Namshi\JOSE\SimpleJWS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
    const TEMPLATE_DIR = '/app/Resources/templates/';

    /**
     * The user entity.
     *
     * @var null|User
     */
    private $userEntity = null;

    /**
     * The container for the current user.
     *
     * @var null
     */
    protected $user = null;

    /**
     * The serializer.
     *
     * @var \JMS\Serializer\Serializer|null
     */
    protected $serializer = null;

    /**
     * CController constructor.
     */
    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

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
        $jws = $this->getJWS();

        if (is_null($this->user) && !is_null($jws)) {
            $this->user = $jws->getPayload();
        }

        return $this->user;
    }

    /**
     * Returns the user entity from the database.
     *
     * @return User|null|object
     */
    protected function getUserEntity()
    {
        if (is_null($this->userEntity)) {
            $em = $this->getDoctrine()->getManager();
            $this->userEntity = $em->getRepository('App:User')->find($this->getUser()['uid']);
        }

        return $this->userEntity;
    }

    /**
     * Returns the JWS object.
     *
     * @return \Namshi\JOSE\JWS|null
     */
    protected function getJWS()
    {
        $request = Request::createFromGlobals();

        if (!empty($request->headers->get('authorization'))) {
            $jws = SimpleJWS::load($request->headers->get('authorization'));
            $key = openssl_pkey_get_public('file://' . $this->get('kernel')->getRootDir() . '/var/jwt/public.pem');

            if ($jws->isValid($key, 'RS256')) {
                return $jws;
            }
        }

        return null;
    }

    /**
     * Does a simple page render without any template engines.
     *
     * @param string $module
     * @param string $file
     * @param bool   $out
     *
     * @return Response|string
     */
    protected function renderHTML($module, $file, $out = true)
    {
        $templateFile = sprintf(
            '%1$s%2$s/%3$s.html',
            /** 1 */ self::TEMPLATE_DIR,
            /** 2 */ $module,
            /** 3 */ $file
        );

        $html = $this->readFile($templateFile);

        if ($out === true) {
            return new Response($html);
        } else {
            return $html;
        }
    }

    /**
     * Reads the file relative to the root directory and returns it's contents.
     *
     * @param   string  $filename
     *
     * @return  string
     */
    protected function readFile($filename)
    {
        return FS::readFile($this->rootDir() . '/../' . $filename);
    }
}
