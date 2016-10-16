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
                'name' => 'LINE_CHART'
            ],
            [
                'id' => 1,
                'name' => 'PIE_CHART',
            ],
            [
                'id' => 2,
                'name' => 'BAR_CHART',
            ],
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
