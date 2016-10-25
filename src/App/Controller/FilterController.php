<?php

namespace App\Controller;

use App\Util\Sql;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;

/**
 * Filter controller for getting different filter selects populated.
 *
 * @Route("api/filters/")
 * @Method("POST")
 *
 * @package App\Controller
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class FilterController extends CController
{
    /**
     * Action for fetching date time scales for filters.
     *
     * @Permission
     *
     * @Route("timescale")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getDateRangesAction()
    {
        $result = [
            [
                'id' => 0,
                'name' => 'SCALE_HOUR',
                'type' => Sql::DATE_FORMAT_HOUR,
            ],
            [
                'id' => 1,
                'name' => 'SCALE_DAY',
                'type' => Sql::DATE_FORMAT_DAY,
            ],
            [
                'id' => 2,
                'name' => 'SCALE_WEEK',
                'type' => Sql::DATE_FORMAT_WEEK,
            ],
            [
                'id' => 3,
                'name' => 'SCALE_MONTH',
                'type' => Sql::DATE_FORMAT_MONTH,
            ],
            [
                'id' => 4,
                'name' => 'SCALE_YEAR',
                'type' => Sql::DATE_FORMAT_YEAR,
            ]
        ];

        return new JsonResponse($result);
    }

    /**
     * A route for fetching date range preselect options for date range selection.
     *
     * @Permission
     *
     * @Route("date/ranges")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getDateRangePreSelectsAction()
    {
        $ranges = [
            [
                'id' => 1,
                'name' => 'RANGE_TODAY',
                'start' => date('Y-m-d') . ' 00:00:00',
                'end' => date('Y-m-d') . ' 23:59:59'
            ]
        ];

        $start = new \DateTime('yesterday');
        $end = new \DateTime('yesterday');

        $ranges[] = [
            'id' => 2,
            'name' => 'RANGE_YESTERDAY',
            'start' => $start->format('Y-m-d') . ' 00:00:00',
            'end' => $end->format('Y-m-d') . ' 23:59:59'
        ];

        $start = new \DateTime('last monday');
        $end = new \DateTime('next sunday');

        $ranges[] = [
            'id' => 3,
            'name' => 'RANGE_THIS_WEEK',
            'start' => $start->format('Y-m-d') . ' 00:00:00',
            'end' => $end->format('Y-m-d') . ' 23:59:59'
        ];

        $ranges[] = [
            'id' => 4,
            'name' => 'RANGE_LAST_WEEK',
            'start' => date('Y-m-d', strtotime('last monday - 7 days')) . ' 00:00:00',
            'end' => date('Y-m-d', strtotime('next sunday - 7 days')) . ' 23:59:59'
        ];

        $start = new \DateTime('first day of this month');
        $end = new \DateTime('last day of this month');

        $ranges[] = [
            'id' => 5,
            'name' => 'RANGE_THIS_MONTH',
            'start' => $start->format('Y-m-d') . ' 00:00:00',
            'end' => $end->format('Y-m-d') . ' 23:59:59'
        ];

        $start = new \DateTime('first day of last month');
        $end = new \DateTime('last day of last month');

        $ranges[] = [
            'id' => 6,
            'name' => 'RANGE_LAST_MONTH',
            'start' => $start->format('Y-m-d') . ' 00:00:00',
            'end' => $end->format('Y-m-d') . ' 23:59:59'
        ];

        $ranges[] = [
            'id' => 7,
            'name' => 'RANGE_CURRENT_YEAR',
            'start' => date('Y-m-d', strtotime('first day of January ' . date('Y'))) . ' 00:00:00',
            'end' => date('Y-m-d', strtotime('last day of December ' . date('Y'))) . ' 23:59:59'
        ];

        $ranges[] = [
            'id' => 8,
            'name' => 'RANGE_LAST_YEAR',
            'start' => date('Y-m-d', strtotime('first day of January ' . ((int)date('Y') - 1))) . ' 00:00:00',
            'end' => date('Y-m-d', strtotime('last day of December ' . ((int)date('Y') - 1))) . ' 23:59:59'
        ];

        return new JsonResponse($ranges);
    }

    /**
     * Action for fetching chart types for filters.
     *
     * @Permission
     *
     * @Route("charttype")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getChartTypesAction()
    {
        $result = [
            [
                'id' => 0,
                'name' => 'LINE_CHART',
                'type' => 'line'
            ],
            [
                'id' => 1,
                'name' => 'PIE_CHART',
                'type' => 'pie'
            ],
            [
                'id' => 2,
                'name' => 'BAR_CHART',
                'type' => 'bar'
            ],
        ];

        return new JsonResponse($result);
    }

    /**
     * Action for fetching aggregate method options.
     *
     * @Permission
     *
     * @Route("aggregates")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getAggregateMethodsAction()
    {
        $result = [
            [
                'id' => Sql::AGGREGATE_SUM,
                'name' => 'AGGREGATE_SUM',
            ],
            [
                'id' => Sql::AGGREGATE_AVERAGE,
                'name' => 'AGGREGATE_AVERAGE',
            ],
            [
                'id' => Sql::AGGREGATE_COUNT,
                'name' => 'AGGREGATE_COUNT',
            ],
            [
                'id' => Sql::AGGREGATE_MAX,
                'name' => 'AGGREGATE_MAX',
            ],
            [
                'id' => Sql::AGGREGATE_MIN,
                'name' => 'AGGREGATE_MIN',
            ]
        ];

        return new JsonResponse($result);
    }

    /**
     * Action for fetching saved search settings for trend chart.
     *
     * @Permission
     *
     * @Route("setting/trend")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getTrendSearchSettingAction()
    {
        $result = [];

        return new JsonResponse($result);
    }
}
