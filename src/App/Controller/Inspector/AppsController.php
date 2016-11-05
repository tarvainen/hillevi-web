<?php

namespace App\Controller\Inspector;

use App\Controller\CController;
use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for the inspector apps data.
 *
 * @package App\Controller\Inspector
 *
 * @Route("api/inspector/apps/")
 * @Method("POST")
 */
class AppsController extends CController
{
    /**
     * Action for fetching users own data.
     *
     * @Permission
     *
     * @Route("all")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getAllDataAction()
    {
        list($startDate, $endDate) = $this->mapFromRequest(['startDate', 'endDate']);

        if (!isset($startDate)) {
            $startDate = new \DateTime('1900-01-01');
        } else {
            $startDate = new \DateTime($startDate);
        }

        if (!isset($endDate)) {
            $endDate = new \DateTime('2036-01-01');
        } else {
            $endDate = new \DateTime($endDate);
        }

        $dql = sprintf(
            '
              SELECT
                a.name,
                SUM(a.activeTime) as activeTime
              FROM App:ActiveApplication a
              WHERE
                a.user = :userId AND
                a.startTime >= :startTime AND
                a.endTime <= :endTime
              GROUP BY a.name
              ORDER BY activeTime DESC
            '
        );

        /**
         * @var Query $query
         */
        $query = $this
            ->manager()
            ->createQuery($dql)
            ->setParameter('userId', $this->getUserEntity()->getId())
            ->setParameter('startTime', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('endTime', $endDate->format('Y-m-d H:i:s'))
        ;

        $result = $query->getArrayResult();

        return new JsonResponse($result);
    }
}