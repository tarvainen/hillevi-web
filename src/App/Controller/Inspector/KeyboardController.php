<?php

namespace App\Controller\Inspector;

use App\Controller\CController;
use App\Util\DateUtil;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\Query;
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

        /** 1. Fetch summarized keyboard activity data. */

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

        $stmt->closeCursor();
        $stmt = null;

        /** 2. Fetch key combos. */

        $dql = '
            SELECT
              c.combo       as combo,
              SUM(c.amount) as amount
            FROM App:KeyCombo c
            WHERE
              c.user = :userId AND
              c.startTime >= :startTime AND
              c.endTime <= :endTime
            GROUP BY c.user, c.combo
            ORDER BY amount DESC
        ';

        /** @var Query $query */
        $query = $this
            ->manager()
            ->createQuery($dql)
            ->setMaxResults(5)
            ->setParameter('userId', $this->getUserEntity()->getId())
            ->setParameter('startTime', $startTime->format(DateUtil::DATETIME_DB))
            ->setParameter('endTime', $endTime->format(DateUtil::DATETIME_DB));

        $data['keyCombos'] = $query->getArrayResult();

        return new JsonResponse($data);
    }

    /**
     * Action to get the keyboard load data to be shown
     * in the keyboard layout image.
     *
     * @Permission
     *
     * @Route("load")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getKeyboardLoadAction()
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

        $dql = '
            SELECT
              SUM(k.key_F1)           AS keyF1,
              SUM(k.key_F2)           AS keyF2,
              SUM(k.key_F3)           AS keyF3,
              SUM(k.key_F4)           AS keyF4,
              SUM(k.key_F5)           AS keyF5,
              SUM(k.key_F6)           AS keyF6,
              SUM(k.key_F7)           AS keyF7,
              SUM(k.key_F8)           AS keyF8,
              SUM(k.key_F9)           AS keyF9,
              SUM(k.key_F10)          AS keyF10,
              SUM(k.key_F11)          AS keyF11,
              SUM(k.key_F12)          AS keyF12,
              SUM(k.key_PrintScreen)  AS keyPrintScreen,
              SUM(k.key_ScrollLock)   AS keyScrollLock,
              SUM(k.key_Pause)        AS keyPause,
              
              SUM(k.key_1)            AS key1,
              SUM(k.key_2)            AS key2,
              SUM(k.key_3)            AS key3,
              SUM(k.key_4)            AS key4,
              SUM(k.key_5)            AS key5,
              SUM(k.key_6)            AS key6,
              SUM(k.key_7)            AS key7,
              SUM(k.key_8)            AS key8,
              SUM(k.key_9)            AS key9,
              SUM(k.key_10)           AS key0,
              SUM(k.key_Plus)         AS keyPlus,
              SUM(k.key_Insert)       AS keyInsert,
              SUM(k.key_Backspace)    AS keyBackspace,
              SUM(k.key_Home)         AS keyHome,
              SUM(k.key_PageUp)       AS keyPageUp,
              SUM(k.key_NumLock)      AS keyNumLock,
              
              SUM(k.key_Tab)          AS keyTab,
              SUM(k.key_Q)            AS keyQ,
              SUM(k.key_W)            AS keyW,
              SUM(k.key_R)            AS keyR,
              SUM(k.key_T)            AS keyT,
              SUM(k.key_Y)            AS keyY,
              SUM(k.key_U)            AS keyU,
              SUM(k.key_I)            AS keyI,
              SUM(k.key_O)            AS keyO,
              SUM(k.key_P)            AS keyP,
              SUM(k.key_Tilde)        AS keyTilde,
              SUM(k.key_Delete)       AS keyDelete,
              SUM(k.key_End)          AS keyEnd,
              SUM(k.key_PageDown)     AS keyPageDown,
              
              SUM(k.key_CapsLock)     AS keyCapsLock,
              SUM(k.key_A)            AS keyA,
              SUM(k.key_S)            AS keyS,
              SUM(k.key_D)            AS keyD,
              SUM(k.key_F)            AS keyF,
              SUM(k.key_G)            AS keyG,
              SUM(k.key_H)            AS keyH,
              SUM(k.key_J)            AS keyJ,
              SUM(k.key_K)            AS keyK,
              SUM(k.key_L)            AS keyL,
              SUM(k.key_OE)           AS keyOE,
              SUM(k.key_AE)           AS keyAE,
              SUM(k.key_Multiply)     AS keyMultiply,
              
              SUM(k.key_LeftShift)    AS keyLeftShift,
              SUM(k.key_Z)            AS keyZ,
              SUM(k.key_X)            AS keyX,
              SUM(k.key_C)            AS keyC,
              SUM(k.key_V)            AS keyV,
              SUM(k.key_B)            AS keyB,
              SUM(k.key_N)            AS keyN,
              SUM(k.key_M)            AS keyM,
              SUM(k.key_Comma)        AS keyComma,
              SUM(k.key_Period)       AS keyPeriod,
              SUM(k.key_Minus)        AS keyMinus,
              SUM(k.key_RightShift)   AS keyRightShift,
              SUM(k.key_Up)           AS keyUp,
              
              SUM(k.key_LeftCtrl)     AS keyLeftCtrl,
              SUM(k.key_LeftAlt)      AS keyLeftAlt,
              SUM(k.key_Space)        AS keySpace,
              SUM(k.key_RightCtrl)    AS keyRightCtrl,
              SUM(k.key_Left)         AS keyLeft,
              SUM(k.key_Down)         AS keyDown,
              SUM(k.key_Right)        AS keyRight

            FROM App:KeyStroke k
            WHERE
             k.startTime >= :startTime AND
             k.endTime <= :endTime AND
             k.user = :userId
            GROUP BY k.user
        ';

        /** @var Query $query */
        $query = $this
            ->manager()
            ->createQuery($dql)
            ->setParameter('startTime', $startTime->format(DateUtil::DATETIME_DB))
            ->setParameter('endTime', $endTime->format(DateUtil::DATETIME_DB))
            ->setParameter('userId', $this->getUserEntity()->getId());

        $result = $query->getArrayResult();

        if (empty($result)) {
            $result = [];
        } else {
            $result = $result[0];
        }

        return new JsonResponse($result);
    }

    /**
     * Action for fetching typing speed for the dashboard widget.
     *
     * @Permission
     *
     * @Route("typing/speed")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getKeyboardTypingSpeedAction()
    {
        list($startTime, $endTime) = $this->mapFromRequest(['startTime', 'endTime']);

        $startTime = DateUtil::validate($startTime, new \DateTime('today midnight'));
        $endTime = DateUtil::validate($endTime, new \DateTime('tomorrow midnight - 1 minute'));

        $labels = [];

        for ($i = 0; $i < 24; $i++) {
            $labels[] = $i + 1;
        }

        $sql = sprintf(
            '
              SELECT
                AVG(i.typingSpeed) as typingSpeed,
                HOUR(i.startTime)  as hour
              FROM inspection_data_summary i
              WHERE
                i.user_id = %1$d AND
                i.startTime >= "%2$s" AND
                i.endTime <= "%3$s"
              GROUP BY HOUR(i.startTime)
              ORDER BY HOUR(i.startTime);
            ',
            /** 1 */ $this->getUserEntity()->getId(),
            /** 2 */ $startTime->format(DateUtil::DATETIME_DB),
            /** 3 */ $endTime->format(DateUtil::DATETIME_DB)
        );

        /** @var Statement $stmt */
        $stmt = $this->manager()->getConnection()->query($sql);

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = [];

        // Format the data to the right format for the chart
        foreach ($labels as $label) {
            $data[$label] = 0;
        }

        foreach ($result as $item) {
            $data[$item['hour']] = (float) $item['typingSpeed'];
        }

        $response = [
            'labels' => $labels,
            'data'   => [array_values($data)]
        ];

        return new JsonResponse($response);
    }
}