<?php

namespace App\Controller;

use App\Entity\ApiReader;
use App\Exception\ActionFailedException;
use App\Exception\NotFoundException;
use App\Naming\FieldType;
use App\Util\Logger;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Annotation\Permission;

/**
 * Controller to handle interfaces.
 *
 * @package App\Controller
 *
 * @Route("api/interface/")
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class InterfaceController extends CController
{
    /**
     * Returns a list of all available interfaces for current user.
     *
     * @Permission("perm=interface")
     *
     * @Route("all")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getAllInterfaceAction()
    {
        $query = $this
            ->getDoctrine()
            ->getEntityManager()
            ->createQueryBuilder()
            ->from('App:ApiReader', 'a')
            ->select('partial a.{id, name, type, url, columns, lastUpdate, active}')
            ->where('a.owner = :id')
            ->setParameter(':id', $this->getUser()['uid'])
            ->getQuery();

        return new JsonResponse($query->getArrayResult());
    }

    /**
     * The route for creating new interface.
     *
     * @Permission("perm=interface:create")
     *
     * @Route("create")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addInterfaceAction(Request $request)
    {
        $data = $this->mapHashFromRequest(['name', ' type', 'url']);

        $data['tableName'] = preg_replace('/[^A-Za-z_]/', '', strtolower($data['name']));
        $data['active'] = true;
        $data['columns'] = [];

        $api = new ApiReader();
        $api->fromArray($data);

        $api->setOwner($this->getUserEntity());
        $api->setLastUpdate(new \DateTime());

        $em = $this->getDoctrine()->getManager();

        $em->persist($api);
        $em->flush();

        if (!$em->contains($api)) {
            throw new ActionFailedException('save');
        }

        return new JsonResponse('OK');
    }

    /**
     * Route to delete an interface.
     *
     * @Permission("perm=interface:delete")
     *
     * @Route("delete/{id}")
     * @Method("POST")
     *
     * @param int $id
     *
     * @throws NotFoundException
     *
     * @return JsonResponse
     */
    public function removeInterfaceAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $api = $em->find('App:ApiReader', $id);

        if (!$api) {
            throw new NotFoundException('interface');
        }

        $em->remove($api);
        $em->flush();

        return new JsonResponse('OK');
    }

    /**
     * Route for updating interfaces information.
     *
     * @Permission("perm=interface:create")
     *
     * @Route("update", requirements={"id": "\d+"})
     * @Method("POST")
     */
    public function updateInterfaceAction(Request $request)
    {
        $id = $request->get('id');

        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();

        /**
         * @var ApiReader $api
         */
        $api = $em->find('App:ApiReader', $id);

        $data = $this->mapHashFromRequest(['name', 'type', 'url', 'columns']);
        $api->fromArray($data);

        $em->persist($api);
        $em->flush();

        return new JsonResponse('OK');
    }

    /**
     * Action to fetch all possible interface types.
     *
     * @Route("fields/types")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getInterfaceTypesAction()
    {
        $types = [
            FieldType::INTEGER,
            FieldType::DECIMAL,
            FieldType::STRING
        ];

        return new JsonResponse($types);
    }
}
