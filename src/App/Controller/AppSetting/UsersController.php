<?php

namespace App\Controller\AppSetting;
use App\Controller\CController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for the app user settings.
 *
 * @package App\Controller
 *
 * @Route("api/appsetting/users/")
 * @Method("POST")
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class UsersController extends CController
{
    /**
     * Action to find all users.
     *
     * @Permission
     *
     * @Route("find")
     * @Method("POST")
     *
     * @return Response
     */
    public function findAction()
    {
        $data = $this
            ->manager()
            ->getRepository('App:User')
            ->findAll();

        return new Response(
            $this->serializer->serialize(
                $data, 'json', SerializationContext::create()->setGroups(['list'])
            )
        );
    }
}