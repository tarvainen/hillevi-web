<?php

namespace App\Controller;

use App\Entity\Notification;
use Doctrine\ORM\EntityManager;
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
     * @Permission("notification:all")
     *
     * @return Response
     */
    public function getNotificationsAction($amount, Request $request)
    {
        $notifications = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:Notification')
            ->findBy([
                'user' => $this->getUserEntity()->getId(),
                'dismissed' => false
            ]);

        return new Response(
            $this->serializer->serialize(
                $notifications,
                'json',
                SerializationContext::create()->setGroups(['common'])
            )
        );
    }

    /**
     * Route for dismissing the notifications.
     *
     * @Route("dismiss")
     * @Method("POST")
     *
     * @Permission("notification:all")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function dismissNotificationsAction(Request $request)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $notifications = $em
            ->getRepository('App:Notification')
            ->findBy([
                'user' => $this->getUserEntity()->getId(),
                'id' => $request->get('id', [])
            ]);

        foreach ($notifications as $notification) {
            /** @var Notification $notification */
            $notification->setDismissed(true);
            $em->persist($notification);
        }

        $em->flush();

        return new Response('OK');
    }
}
