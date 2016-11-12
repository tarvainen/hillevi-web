<?php

namespace App\Controller;

use App\Entity\ApiReader;
use App\Exception\ActionFailedException;
use App\Util\Arrays;
use App\Util\Json;
use App\Util\Sql;
use Doctrine\DBAL\Driver\PDOStatement;
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
                    $column = [
                        'name' => $col['name'],
                        'api' => $item->getName(),
                        'table' => $item->getTableName(),
                        'field' => $col['field'],
                        'unit' => isset($col['unit']) ? $col['unit'] : '',
                        'aggregate' => isset($col['aggregate']) ? $col['aggregate'] : Sql::AGGREGATE_SUM
                    ];

                    // Format the proper display name for the column
                    $displayName = sprintf(
                        '%1$s (%2$s::%3$s) [%4$s]',
                        /** 1 */ $column['name'],
                        /** 2 */ $column['api'],
                        /** 3 */ $column['field'],
                        /** 4 */ Sql::$aggregates[$column['aggregate']]
                    );

                    $column['displayName'] = $displayName;

                    $columns[] = $column;
                }
            }
        }

        return new JsonResponse($columns);
    }

    /**
     * Action to fetch chart data.
     *
     * @param Request $request
     *
     * @Route("data")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getDataAction(Request $request)
    {
        list($startDate, $endDate, $columns, $scale) = $this->mapFromRequest(
            ['startDateTime', 'endDateTime', 'columns', 'scale']
        );

        if (empty($columns)) {
            throw new ActionFailedException('GetData');
        }

        foreach ($columns as &$column) {
            $column = Json::decode($column);
        }

        // Check that current user has rights to all of the defined columns
        $api = $this->manager()->getRepository('App:ApiReader')
            ->findOneBy(
                [
                    'table' => Arrays::map($columns, 'table'),
                    'owner' => $this->getUserEntity()->getId()
                ]
            );

        if (!$api) {
            throw new ActionFailedException('GetData');
        }

        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);

        $data = $this->fetchData($start, $end, $columns, $scale);

        return new JsonResponse($data);
    }

    /**
     * Fetch data for the date range.
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param array $columns
     * @param $groupBy
     * @return array
     */
    private function fetchData(\DateTime $startDate, \DateTime $endDate, array $columns, $groupBy = '%m/%Y')
    {
        $conn = $this->getDoctrine()->getConnection();

        $labels = $this->getLabels($startDate, $endDate, $groupBy);

        $series = [];
        $result = [];

        $grouping = $this->getGroupBy($groupBy);

        foreach ($columns as $column) {
            if ($groupBy !== '%d.%m %k') {
                $sql = sprintf(
                    '
                  SELECT
                    DATE_FORMAT(T.FULLDATE, "%1$s") AS label,
                    ROUND(%2$s(x.%3$s), 2) AS %3$s
                  FROM
                    TIME_DIMENSION T
                  CROSS JOIN %4$s x ON DATE(x.REQUESTED_AT) = T.FULLDATE 
                  WHERE
                    T.FULLDATE BETWEEN "%5$s" AND "%6$s"
                  GROUP BY %7$s
                  ORDER BY T.FULLDATE;
                ',
                /** 1 */ $groupBy,
                /** 2 */ Sql::$aggregates[$column['aggregate']],
                /** 3 */ $column['field'],
                /** 4 */ $column['table'],
                /** 5 */ $startDate->format('Y-m-d'),
                /** 6 */ $endDate->format('Y-m-d'),
                /** 7 */ $grouping
                );
            } else {
                $sql = sprintf(
                    '
                      SELECT
                        DATE_FORMAT(x.REQUESTED_AT, "%1$s:00") AS label,
                        ROUND(%2$s(x.%3$s), 2) AS %3$s
                      FROM
                        %4$s x
                      WHERE
                        x.REQUESTED_AT BETWEEN "%5$s" AND "%6$s"
                      GROUP BY DATE_FORMAT(x.REQUESTED_AT, "%1$s")
                      ORDER BY x.REQUESTED_AT;
                    ',
                    /** 1 */ '%d.%m %H',
                    /** 2 */ Sql::$aggregates[$column['aggregate']],
                    /** 3 */ $column['field'],
                    /** 4 */ $column['table'],
                    /** 5 */ $startDate->format('Y-m-d H:i:s'),
                    /** 6 */ $endDate->format('Y-m-d H:i:s')
                );
            }

            /**
             * @var PDOStatement $stmt
             */
            $stmt = $conn->query($sql);

            $result[] = $this->formatData($labels, $stmt->fetchAll(\PDO::FETCH_ASSOC), $column['field']);

            $series[] = sprintf(
                '%1$s (%2$s::%3$s)[%4$s] %5$s',
                /** 1 */ $column['name'],
                /** 2 */ $column['api'],
                /** 3 */ $column['field'],
                /** 4 */ Sql::$aggregates[$column['aggregate']],
                /** 5 */ $column['unit'] ? '(' . $column['unit'] . ')' : ''
            );

            $stmt->closeCursor();
            $stmt = null;
        }

        return [
            'data' => $result,
            'labels' => $labels,
            'series' => $series
        ];
    }

    /**
     * Format the data so there will be data for every single date value.
     *
     * @param array  $labels
     * @param array  $data
     * @param string $fieldName
     *
     * @return array
     */
    private function formatData($labels, $data, $fieldName)
    {
        $result = [];

        foreach ($labels as $label) {
            $result[$label] = 0;
        }

        foreach ($data as $row) {
            $result[$row['label']] = $row[$fieldName];
        }

        return array_values($result);
    }

    /**
     * Fetches labels by the date range and format.
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param string $groupBy
     *
     * @return array
     */
    private function getLabels(\DateTime $startDate, \DateTime $endDate, $groupBy)
    {
        $grouping = $this->getGroupBy($groupBy);

        if ($groupBy !== '%d.%m %k') {
            $sql = sprintf(
                '
              SELECT DISTINCT
                DATE_FORMAT(T.FULLDATE, "%1$s") AS label
              FROM
                TIME_DIMENSION T 
              WHERE
                T.FULLDATE BETWEEN "%2$s" AND "%3$s"
              GROUP BY %4$s
              ORDER BY T.FULLDATE;
            ',
                /** 1 */ $groupBy,
                /** 2 */ $startDate->format('Y-m-d'),
                /** 3 */ $endDate->format('Y-m-d'),
                /** 4 */ $grouping
            );
        } else {
            $labels = [];

            $date = clone $startDate;

            while ($date->getTimestamp() < $endDate->getTimestamp()) {
                $labels[] = $date->format('d.m H:00');
                $date->add(\DateInterval::createFromDateString('1 hour'));
            }

            return $labels;
        }

        /**
         * @var PDOStatement $stmt
         */
        $stmt = $this->getDoctrine()->getConnection()->query($sql);
        $data = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $stmt->closeCursor();
        $stmt = null;

        return $data;
    }

    /**
     * Determines the used group by criteria.
     *
     * @param  string $groupBy
     *
     * @return string
     */
    private function getGroupBy($groupBy)
    {
        $grouping = 'T.YEAR';

        switch ($groupBy) {
            case '%m/%Y':
                $grouping .= ', T.MONTH_NUMBER';
                break;
            case '%x/%v':
                $grouping .= ', T.WEEK_NUMBER';
                break;
            case '%d.%m':
                $grouping .= ', T.DAY_OF_YEAR';
                break;
        }

        return $grouping;
    }
}
