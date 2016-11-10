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
    const MODE_WHOLE_TIME  = 'total';
    const MODE_ONLINE_TIME = 'online';

    /**
     * Action for fetching the activity percentage for the specified date rang (by the total active time).
     *
     * @Permission
     *
     * @Route("activity/{mode}")
     * @Method("POST")
     *
     * @param string $mode  Used mode in data fetch. Default 'total'.
     *
     * @return JsonResponse
     */
    public function getActivityPercentageAction($mode)
    {
        list($startTime, $endTime) = $this->mapFromRequest(['startDate', 'endDate']);

        $startTime = DateUtil::validate($startTime, new \DateTime('today midnight'));
        $endTime = DateUtil::validate($endTime, new \DateTime('tomorrow midnight - 1 minute'));

        $timeSumClause = 'SUM(TIMESTAMPDIFF(SECOND, c.startTime, c.endTime))';

        if ($mode === self::MODE_WHOLE_TIME) {
            $timeSumClause = sprintf(
                'TIMESTAMPDIFF(SECOND, "%1$s", "%2$s")',
                /** 1 */ $startTime->format(DateUtil::DATETIME_DB),
                /** 2 */ $endTime->format(DateUtil::DATETIME_DB)
            );
        }

        $sql = sprintf(
            '
              SELECT
                ROUND(
                  ((SUM(c.activeUsage) / 1000) / %1$s) * 100
                , 2) as activePercentage
              FROM computer_usage_snapshot c
              WHERE
                c.user_id = %2$s AND
                c.startTime >= "%3$s" AND
                c.endTime <= "%4$s"
              GROUP BY c.user_id
            ',
            /** 1 */ $timeSumClause,
            /** 2 */ $this->getUserEntity()->getId(),
            /** 3 */ $startTime->format(DateUtil::DATETIME_DB),
            /** 4 */ $endTime->format(DateUtil::DATETIME_DB)
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