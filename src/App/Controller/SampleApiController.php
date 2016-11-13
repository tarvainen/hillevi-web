<?php

namespace App\Controller;

use App\Util\Random;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller to set up some sample apis to test for.
 *
 * TODO: do we need this? Really?
 *
 * @Route("example/")
 *
 * @package App\Controller
 */
class SampleApiController extends CController
{
    /**
     * An action to get some sample data.
     *
     * @Permission
     *
     * @Route("json/simple/{type}/{min}/{max}")
     * @Method("POST")
     *
     * @param string $type
     * @param int    $min
     * @param int    $max
     *
     * @return JsonResponse
     */
    public function simpleOneLevelJsonResponseAction($type, $min, $max)
    {
        $min = (int)$min;
        $max = (int)$max;

        switch ($type) {
            case 'integer':
                return new JsonResponse([
                    'value' => Random::integer($min, $max)
                ]);
                break;
            case 'decimal':
                return new JsonResponse([
                    'value' => Random::decimal($min, $max, 2)
                ]);
                break;
            default:
                return new JsonResponse('');
        }
    }
}
