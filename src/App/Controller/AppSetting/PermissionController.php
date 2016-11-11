<?php

namespace App\Controller\AppSetting;

use App\Controller\CController;
use App\Entity\User;
use App\Exception\ActionFailedException;
use App\Util\Logger;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for the app setting permissions.
 *
 * @Route("api/appsetting/permissions/")
 * @Method("POST")
 *
 * @package App\Controller\AppSetting
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class PermissionController extends CController
{
    /**
     * Action to fetch list of available permissions.
     *
     * @Permission
     *
     * @Route("find")
     * @Method("POST")
     *
     * @return Response
     */
    public function getPermissionsAction()
    {
        $data = $this
            ->manager()
            ->getRepository('App:Permission')
            ->findAll();

        return new Response(
            $this->serializer->serialize(
                $data, 'json'
            )
        );
    }

    /**
     * Action to fetch the permissions for the requested users.
     *
     * @Permission
     *
     * @Route("users")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getUsersPermissionsAction(Request $request)
    {
        $userIds = $request->get('users', []);

        $data = $this
            ->manager()
            ->getRepository('App:User')
            ->findById($userIds);

        $result = [];

        // Format data

        /** @var User $user */
        foreach ($data as $user) {
            $mapped = [
                'id' => $user->getId(),
                'name' => $user->getFullName(),
                'permissions' => []
            ];

            $rights = $user->getRights()->toArray();

            /** @var \App\Entity\Permission $right */
            foreach ($rights as $right) {
                $mapped['permissions'][$right->getName()] = true;
            }

            $result[] = $mapped;
        }
        
        return new JsonResponse($result);
    }

    /**
     * Action to save user's permissions.
     *
     * @Permission
     *
     * @Route("save")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function saveUserPermissionsAction(Request $request)
    {
        $permissions = $request->get('permissions', []);

        foreach ($permissions as $userPermission) {
            /** @var User $user */
            $user = $this
                ->manager()
                ->find('App:User', $userPermission['id']);

            if (!$user) {
                throw new ActionFailedException('No user found');
            }

            if (!isset($userPermission['permissions'])) {
                $userPermission['permissions'] = [];
            }

            $this->saveUsersPermissions($user, $userPermission['permissions']);
        }

        return new JsonResponse('OK');
    }

    /**
     * Saves user's permissions.
     *
     * @param User  $user
     * @param array $permissions
     *
     * @return void
     */
    private function saveUsersPermissions(User $user, $permissions)
    {
        $usersPermissions = $user->getRights();

        foreach ($permissions as $permission => $on) {
            $exists =  ($usersPermissions->exists(function ($i, $p) use ($permission) {
                /** @var \App\Entity\Permission $p */
                return $permission == $p->getName();
            }));

            $pEntity = $this
                ->manager()
                ->getRepository('App:Permission')
                ->findOneByName($permission)
            ;

            if (!$pEntity) {
                break;
            }

            if (!$exists && $on === 'true') {
                $user->getRights()->add($pEntity);
            } else if ($exists && $on === 'false') {
                $user->getRights()->removeElement($pEntity);
            }

            $this->manager()->flush();
        }
    }
}