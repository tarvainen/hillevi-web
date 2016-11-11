<?php
namespace App\Controller;

use App\Entity\User;
use App\Exception\ActionFailedException;
use App\Exception\UnauthorizedException;
use App\Util\Password;
use Doctrine\ORM\EntityManager;
use Namshi\JOSE\SimpleJWS;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Annotation\Permission;

/**
 * Controller for the authentication routes.
 *
 * @package App\Controller
 *
 * @Route("api/auth/")
 *
 * @author  Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class AuthController extends CController
{
    /**
     * Returns currently signed user's data.
     *
     * @Permission
     *
     * @Route("me")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function meAction()
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder()
            ->from('App:User', 'u')
            ->select(array('partial u.{id, firstname, lastname, username, email, apiKey}'))
            ->where('u.id = ' . $this->getUser()['uid'])
            ->setMaxResults(1)
            ->getQuery();

        $user = $query->getArrayResult()[0];
        $user['ui'] = $this->getUiSettings();

        return new JsonResponse($user);
    }

    /**
     * Returns the user's settings.
     *
     * @Permission
     *
     * @Route("settings")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function settingsAction()
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder()
            ->from('App:User', 'u')
            ->select(array('partial u.{id, firstname, lastname, username, email}'))
            ->where('u.id = ' . $this->getUser()['uid'])
            ->setMaxResults(1)
            ->getQuery();


        return new JsonResponse($query->getArrayResult()[0]);
    }

    /**
     * Updates the user's settings.
     *
     * @Permission
     *
     * @Route("settings/save")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request)
    {
        $user = $this->getUserEntity();

        $user->fromArray($request->request->all());

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->meAction();
    }

    /**
     * Route to just check the user password for password change.
     *
     * @param Request $request
     *
     * @Permission
     *
     * @Route("settings/password/check")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function passwordCheckAction(Request $request)
    {
        $passwordToCheck = $request->get('password');

        if (Password::test($passwordToCheck, $this->getUserEntity()->getPassword())) {
            return new JsonResponse('OK');
        }

        throw new ActionFailedException('password_check');
    }

    /**
     * Route to change the user's password.
     *
     * @Permission
     *
     * @Route("settings/password/change")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function passwordChangeAction()
    {
        $values = ['oldPassword', 'newPassword', 'newPasswordAgain'];

        list($oldPassword, $newPassword, $newPasswordAgain) = $this->mapFromRequest($values);

        if (!Password::test($oldPassword, $this->getUserEntity()->getPassword())
            || $newPassword !== $newPasswordAgain
        ) {
            throw new ActionFailedException('password_change');
        }

        $this->getUserEntity()->setPassword($newPassword);

        $em = $this->getDoctrine()->getManager();
        $em->persist($this->getUserEntity());

        $em->flush();

        return new JsonResponse('OK');
    }

    /**
     * Route to log the user in. Adds the JW-token to the cookies.
     *
     * @Route("login")
     * @Method("POST")
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        $username = $request->get('username', '');
        $password = $request->get('password', '');

        $em = $this->getDoctrine()->getManager();

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
            ));

            $privateKey = $this->getPrivateKey();

            $jws->sign($privateKey);

            $token = $jws->getTokenString();

            $user->setToken($token);

            if (empty($user->getApiKey())) {
                $user->refreshApiKey();
            }

            $em->persist($user);
            $em->flush();

            return new Response($token);
        }

        throw new ActionFailedException('login');
    }

    /**
     * Route to log the user out. Clears the cookies.
     *
     * @Permission
     *
     * @Route("logout")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function logoutAction()
    {
        $user = $this->getUserEntity();

        if (!$user) {
            throw new UnauthorizedException();
        }

        $user->setToken(null);

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        return new JsonResponse('OK');
    }

    /**
     * Get ui setting for the current user. This setting determines which components
     * is shown in the ui for the user.
     *
     * @return array
     */
    private function getUiSettings()
    {
        $settings = [];
        $user = $this->getUserEntity();

        $permissions = $this
            ->manager()
            ->getRepository('App:Permission')
            ->findAll();

        foreach ($permissions as $permission) {
            $settings[$permission->getName()] = $user->hasRight($permission->getName());
        }

        return $settings;
    }
}
