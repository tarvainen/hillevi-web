<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\UnauthorizedException;
use App\Util\FS;
use JMS\Serializer\SerializerBuilder;
use Namshi\JOSE\SimpleJWS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
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
    public function log($msg = '')
    {
        $logger = $this->get('logger');
        $logger->debug($msg);
    }

    /**
     * Returns the user entity from the database.
     *
     * @return User|null|object
     */
    public function getUserEntity()
    {
        if (is_null($this->userEntity)) {
            $em = $this->getDoctrine()->getManager();
            $this->userEntity = $em->getRepository('App:User')->find($this->getUser()['uid']);
        }

        return $this->userEntity;
    }

    /**
     * Returns the current user. If the user is not authenticated, the null will be returned.
     *
     * @return array|null
     */
    public function getUser()
    {
        $jws = $this->getJWS();

        if (is_null($this->user) && !is_null($jws)) {
            $this->user = $jws->getPayload();
        }

        return $this->user;
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
     * Returns the JWS object.
     *
     * @return \Namshi\JOSE\JWS|null
     */
    protected function getJWS()
    {
        $request = $this->getRequest();

        if (!empty($request->headers->get('authorization'))) {
            try {
                $jws = SimpleJWS::load($request->headers->get('authorization'));
            } catch (\InvalidArgumentException $e) {
                throw new UnauthorizedException();
            }

            $key = openssl_pkey_get_public('file://' . $this->get('kernel')->getRootDir() . '/var/jwt/public.pem');

            if ($jws->isValid($key, 'RS256')) {
                $jws->sign($this->getPrivateKey());
                return $jws;
            }
        }

        throw new UnauthorizedException();
    }

    /**
     * Returns the private key for the JWT.
     *
     * @return bool|resource
     */
    protected function getPrivateKey()
    {
        $key = trim(file_get_contents($this->rootDir() . '/var/jwt/phrase.key'));

        return openssl_pkey_get_private(
            'file://' . $this->rootDir() . '/var/jwt/private.pem',
            $key
        );
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

    /**
     * Maps an array of values from the request. Use like following:
     *
     * list($val, $val1) = $this->mapFromRequest('val', 'val1');
     *
     * @param   array $values
     *
     * @return  array
     */
    protected function mapFromRequest(array $values)
    {
        $request = $this->getRequest();

        $result = array();

        foreach ($values as $value) {
            $result[] = $request->get($value);
        }

        return $result;
    }

    /**
     * Maps the hash from the request.
     *
     * @param   array $values
     *
     * @return  array
     */
    protected function mapHashFromRequest(array $values)
    {
        $request = $this->getRequest();

        $result = array();

        foreach ($values as $value) {
            $result[trim($value)] = $request->get(trim($value));
        }

        return $result;
    }

    /**
     * The test-safe way to get the current request.
     *
     * DO _NOT_ USE Request::createFromGlobals() !!
     *
     * @return mixed
     */
    protected function getRequest()
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }
}
