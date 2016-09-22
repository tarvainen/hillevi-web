<?php
namespace App\Controller;

use Namshi\JOSE\SimpleJWS;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for the authentication routes.
 *
 * @package App\Controller
 *
 * @author  Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class AuthController extends CController
{
    /**
     * Returns currently signed user's data.
     *
     * @Route("api/auth/me")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function actionMe()
    {
        return new JsonResponse($this->getUser());
    }

    /**
     * Route to log the user in. Adds the JW-token to the cookies.
     *
     * @Route("api/auth/login")
     * @Method("POST")
     *
     * @param   Request $request
     *
     * @return JsonResponse
     */
    public function actionLogin(Request $request)
    {
        $username = $request->get('username', '');
        $password = $request->get('password', '');

        $msg = 'ERR'; // TODO: translate (maybe just change to be more exiting)

        // TODO: Check this in this world
        if ($username === 'test' && $password === 'test') {
            $jws = new SimpleJWS(array(
                'alg' => 'RS256'
            ));

            $jws->setPayload(array(
                'userId' => 32
            ));

            $key = trim(file_get_contents($this->rootDir() . '/var/jwt/phrase.key'));

            $privateKey = openssl_pkey_get_private('file://' . $this->rootDir() . '/var/jwt/private.pem', $key);

            $jws->sign($privateKey);

            setcookie('authorization', $jws->getTokenString());

            $msg = 'OK'; // TODO: translate
        }

        return new JsonResponse(
            array(
                'msg' => $msg
            )
        );
    }

    /**
     * Route to log the user out. Clears the cookies.
     *
     * @Route("api/auth/logout")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function actionLogout()
    {
        unset($_COOKIE['authorization']);
        setcookie('authorization', '', time() - 3600);

        return new JsonResponse(
            array(
                'msg' => 'OK', // TODO: translate
            )
        );
    }
}
