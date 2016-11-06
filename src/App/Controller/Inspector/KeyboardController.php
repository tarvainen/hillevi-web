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
 * Controller for the keyboard data.
 *
 * @package App\Controller\Inspector
 *
 * @Route("api/inspector/keyboard/")
 * @Method("POST")
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class KeyboardController extends CController
{
    /**
     * Action to get the keyboard usage summary data.
     *
     * @Permission
     *
     * @Route("summary")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getKeyboardUsageSummaryAction()
    {
        list($startTime, $endTime) = $this->mapFromRequest(['startDate', 'endDate']);

        if (!isset($startTime)) {
            $startTime = new \DateTime('1990-01-01');
        } else {
            $startTime = new \DateTime($startTime);
        }

        if (!isset($endTime)) {
            $endTime = new \DateTime('2036-01-01');
        } else {
            $endTime = new \DateTime($endTime);
        }

        $sql = sprintf(
            'CALL sp_KeyboardInspectionDataSummary(%1$d, "%2$s", "%3$s", 0)',
            /** 1 */ (int) $this->getUserEntity()->getId(),
            /** 2 */ $startTime->format(DateUtil::DATETIME_DB),
            /** 3 */ $endTime->format(DateUtil::DATETIME_DB)
        );

        /** @var Statement $stmt */
        $stmt = $this
            ->manager()
            ->getConnection()
            ->query($sql);

        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return new JsonResponse($data);
    }
}