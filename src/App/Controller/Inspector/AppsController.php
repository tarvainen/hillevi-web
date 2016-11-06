<?php

namespace App\Controller\Inspector;

use App\Controller\CController;
use App\Exception\ActionFailedException;
use App\Util\DateUtil;
use Doctrine\DBAL\Statement;
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
    public function getAllDataRowsAction()
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
            ->setParameter('startTime', $startDate->format(DateUtil::DATETIME_DB))
            ->setParameter('endTime', $endDate->format(DateUtil::DATETIME_DB))
        ;

        $result = $query->getArrayResult();

        return new JsonResponse($result);
    }

    /**
     * Action for fetching usage data for the chart.
     *
     * @Permission
     *
     * @Route("usage-per-time")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getUsagePerTimeAction()
    {
        list($startDate, $endDate, $apps) = $this->mapFromRequest(['startDate', 'endDate', 'apps']);

        if (empty($apps)) {
            throw new ActionFailedException('fetch');
        }

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

        $bindings = [
            ':startTime' => $startDate->format(DateUtil::DATETIME_DB),
            ':endTime' => $endDate->format(DateUtil::DATETIME_DB)
        ];

        $where = '';

        foreach ($apps as $index => $app) {
            $bindings[':name' . $index] = $app;

            $where .= sprintf(
                ' OR app.name LIKE :name%1$s',
                /** 1 */ $index
            );
        }

        $sql = sprintf(
            '
                SELECT
                  app.name as name,
                  SUM(app.activeTime) as activeTime,
                  HOUR(app.startTime) as hour
                FROM active_application app
                WHERE
                  app.startTime >= :startTime AND
                  app.endTime <= :endTime AND
                  (1 = 2 %1$s)
                GROUP BY app.name, HOUR(app.startTime)
                ORDER BY HOUR(app.startTime), app.name
            ',
            /** 1 */ $where
        );

        /** @var Statement $stmt */
        $stmt = $this->manager()->getConnection()->prepare($sql);
        $stmt->execute($bindings);

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $labels = [];

        for ($i = 0; $i < 24; $i++) {
            $labels[] = $i < 10 ? '0' . $i : $i;
        }

        $result = $this->formatUsageChartData($apps, $labels, $data);

        return new JsonResponse([
            'labels' => $labels,
            'series' => $apps,
            'data' => $result
        ]);
    }

    /**
     * Action for fetching the list of the active applications.
     *
     * @Permission
     *
     * @Route("list")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getListOfAppsAction()
    {
        $dql = '
            SELECT DISTINCT
              a.id    as id,
              a.name  as name
            FROM App:ActiveApplication a
            WHERE a.user = :userId
            GROUP BY a.name
            ORDER BY a.name
        ';

        /** @var Query $query */
        $query = $this
            ->manager()
            ->createQuery($dql)
            ->setParameter('userId', $this->getUserEntity()->getId());

        $result = $query->getArrayResult();

        return new JsonResponse($result);
    }

    /**
     * Formats the chart data for the usage chart.
     *
     * @param array $series
     * @param array $labels
     * @param array $data
     *
     * @return array
     */
    private function formatUsageChartData(array $series, array $labels, array $data)
    {
        $tmp = [];
        $base = [];
        $result = [];

        foreach ($labels as $label) {
            $base[$label] = 0;
        }

        foreach ($series as $serie) {
            $tmp[$serie] = $base;
        }

        foreach ($data as $item) {
            $hour = (int)($item['hour']) < 10 ? '0' . $item['hour'] : $item['hour'];
            $tmp[$item['name']][$hour] = (int)$item['activeTime'];
        }

        foreach ($tmp as $item) {
            $result[] = array_values($item);
        }

        return $result;
    }
}