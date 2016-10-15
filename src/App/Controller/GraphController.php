<?php

namespace App\Controller;

use App\Entity\ApiReader;
use App\Exception\ActionFailedException;
use App\Util\Json;
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
        list($startDate, $endDate, $columns) = $this->mapFromRequest(['startDateTime', 'endDateTime', 'columns']);

        if (empty($columns)) {
            throw new ActionFailedException('GetData');
        }

        foreach ($columns as &$column) {
            $column = Json::decode($column);
        }

        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);

        $data = $this->fetchData($start, $end, $columns, '%m/%Y');

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

        foreach ($columns as $column) {
            $sql = sprintf(
                '
                  SELECT
                    DATE_FORMAT(T.FULLDATE, "%1$s") AS label,
                    SUM(x.%2$s) AS %2$s
                  FROM
                    TIME_DIMENSION T
                  CROSS JOIN %3$s x ON DATE(x.REQUESTED_AT) = T.FULLDATE 
                  WHERE
                    T.FULLDATE BETWEEN "%4$s" AND "%5$s"
                  GROUP BY T.YEAR, T.MONTH_NUMBER;
                ',
                /** 1 */ '%m/%Y',
                /** 2 */ $column['field'],
                /** 3 */ $column['table'],
                /** 4 */ $startDate->format('Y-m-d'),
                /** 5 */ $endDate->format('Y-m-d')
            );

            /**
             * @var PDOStatement $stmt
             */
            $stmt = $conn->query($sql);

            $result[] = $this->formatData($labels, $stmt->fetchAll(\PDO::FETCH_ASSOC));
            $series[] = $column['api'] . ':' . $column['field'];
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
     * @param array $labels
     * @param array $data
     *
     * @return array
     */
    private function formatData($labels, $data)
    {
        $result = [];

        foreach ($labels as $label) {
            $result[$label] = 0;
        }

        foreach ($data as $row) {
            $result[$row['label']] = $row['value'];
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
        $sql = sprintf(
            '
              SELECT
                DATE_FORMAT(T.FULLDATE, "%1$s") AS label
              FROM
                TIME_DIMENSION T 
              WHERE
                T.FULLDATE BETWEEN "%2$s" AND "%3$s"
              GROUP BY T.YEAR, T.MONTH_NUMBER
            ',
            /** 1 */ $groupBy,
            /** 2 */ $startDate->format('Y-m-d'),
            /** 3 */ $endDate->format('Y-m-d')
        );

        /**
         * @var PDOStatement $stmt
         */
        $stmt = $this->getDoctrine()->getConnection()->query($sql);
        $data = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $stmt->closeCursor();
        $stmt = null;

        return $data;
    }
}
