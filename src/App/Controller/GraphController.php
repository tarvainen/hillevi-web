<?php

namespace App\Controller;

use App\Entity\ApiReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;

/**
 * Controller for the graph data fetch.
 *
 * @package App\Controller
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 *
 * @Route("api/graph/")
 * @Method("POST")
 */
class GraphController extends CController
{
    /**
     * Action to fetch available columns for graph.
     *
     * @param Request $request
     *
     * @Route("columns/all")
     * @Method("POST")
     *
     * @Permission("graph")
     *
     * @return JsonResponse
     */
    public function getColumnsAction(Request $request)
    {
        $data = $this->manager()
            ->getRepository('App:ApiReader')
            ->my($this->getUserEntity()->getId());

        $columns = [];

        /**
         * @var ApiReader $item
         */
        foreach ($data as $item) {
            $apiColumns = $item->getColumns();

            foreach ($apiColumns as $col) {
                if (in_array($col['type'], ['int', 'decimal'])) {
                    $columns[] =[
                        'api' => $item->getName(),
                        'table' => $item->getTableName(),
                        'field' => $col['field']
                    ];
                }
            }
        }

        return new JsonResponse($columns);
    }
}