<?php

namespace App\Controller\Inspector;

use App\Controller\CController;
use App\Util\DateUtil;
use Doctrine\DBAL\Statement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Computer controller class for computer specific inspector data handling.
 *
 * @package App\Controller\Inspector
 *
 * @Route("api/inspector/computer/")
 * @Method("POST")
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class ComputerController extends CController
{
    /**
     * Action for fetching the activity percentage for the specified date range.
     *
     * @Permission
     *
     * @Route("activity")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getActivityPercentageAction()
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
        
        $sql = sprintf(
            '
              SELECT
                ROUND(
                  ((SUM(c.activeUsage) / 1000) / SUM(TIMESTAMPDIFF(SECOND, c.startTime, c.endTime))) * 100
                , 2) as activePercentage
              FROM computer_usage_snapshot c
              WHERE
                c.user_id = %1$s AND
                c.startTime >= "%2$s" AND
                c.endTime <= "%3$s"
              GROUP BY c.user_id
            ',
            /** 1 */ $this->getUserEntity()->getId(),
            /** 2 */ $startDate->format(DateUtil::DATETIME_DB),
            /** 3 */ $endDate->format(DateUtil::DATETIME_DB)
        );

        /** @var Statement $stmt */
        $stmt = $this->manager()->getConnection()->query($sql);

        $data = $stmt->fetch(\PDO::FETCH_COLUMN);

        return new JsonResponse(
            [
                'percentage' => $data
            ]
        );
    }
}