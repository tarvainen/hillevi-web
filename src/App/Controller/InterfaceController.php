<?php

namespace App\Controller;

use App\Entity\ApiReader;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller to handle interfaces.
 *
 * @package App\Controller
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class InterfaceController extends CController
{
    /**
     * Returns a list of all available interfaces for current user.
     *
     * @Route("api/interface/all")
     * @Method("POST")
     *
     * @return Response
     */
    public function getAllInterfaceAction()
    {
        $interfaces = $this
            ->getDoctrine()
            ->getRepository('App:ApiReader')
            ->findAll();

        $serializer = SerializerBuilder::create()->build();

        return new Response($serializer->serialize($interfaces, 'json'));
    }

    /**
     * The route for creating new interface.
     *
     * @Route("api/interface/create")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addInterfaceAction(Request $request)
    {
        $serializer = SerializerBuilder::create()->build();
        $this->log($this->getUserEntity()->getId());

        /**
         * @var ApiReader $api
         */
        $api = $serializer->deserialize(
            json_encode($request->request->all()),
            'App\Entity\ApiReader',
            'json'
        );

        $api->setOwner($this->getUserEntity());
        $api->setLastUpdate(new \DateTime());

        $em = $this->getDoctrine()->getManager();

        $em->persist($api);
        $em->flush();

        if ($em->contains($api)) {
            return new JsonResponse('OK');
        }

        return new JsonResponse('ERR');
    }
}
