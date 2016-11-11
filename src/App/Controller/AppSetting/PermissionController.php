<?php

namespace App\Controller\AppSetting;

use App\Controller\CController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;
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
}