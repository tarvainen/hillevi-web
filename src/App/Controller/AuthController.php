<?php
namespace App\Controller;

use App\Entity\User;
use App\Util\Password;
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
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function meAction()
    {
        $user = $this->getUser();

        if (!is_null($user)) {
            return new JsonResponse($user);
        }

        return new JsonResponse(null, 401);
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
    public function loginAction(Request $request)
    {
        $username = $request->get('username', '');
        $password = $request->get('password', '');

        $msg = 'ERR'; // TODO: translate (maybe just change to be more exiting)
        $status = 401;

        /**
         * @var User $user
         */
        $user = $this
            ->getDoctrine()
            ->getRepository('App:User')
            ->findOneBy(['username' => $username])
        ;

        if (!is_null($user) && Password::test($password, $user->getPassword())) {
            $jws = new SimpleJWS(array(
                'alg' => 'RS256'
            ));

            /**
             * Set JWT data here.
             */
            $jws->setPayload(array(
                'uid' => $user->getId(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername()
            ));

            $key = trim(file_get_contents($this->rootDir() . '/var/jwt/phrase.key'));

            $privateKey = openssl_pkey_get_private(
                'file://' . $this->rootDir() . '/var/jwt/private.pem',
                $key
            );

            $jws->sign($privateKey);

            $msg = $jws->getTokenString();
            $status = 200;
        }

        return new JsonResponse(
            array(
                'msg' => $msg
            ),
            $status
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
    public function logoutAction()
    {
        // TODO: remove the webtoken from the user from the db so the session wont go on!
        return new JsonResponse(
            array(
                'msg' => 'OK',
            )
        );
    }
}
