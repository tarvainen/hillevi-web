<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Util\Logger;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for notifications.
 *
 * @Route("api/notifications/")
 * @Method("POST")
 *
 * @package App\Controller
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class NotificationController extends CController
{
    /**
     * Route for fetching all notifications.
     *
     * @Route("latest/{amount}")
     * @Method("POST")
     *
     * @Permission("notification")
     *
     * @return Response
     */
    public function getNotificationsAction($amount, Request $request)
    {
        $data = $this
            ->getUserEntity()
            ->getNotifications()
            ->toArray()
        ;

        return new Response(
            $this->serializer->serialize(
                $data,
                'json',
                SerializationContext::create()->setGroups(['common'])
            )
        );
    }
}
