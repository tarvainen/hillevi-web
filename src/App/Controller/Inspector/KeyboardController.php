<?php

namespace App\Controller\Inspector;

use App\Controller\CController;
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
        return new JsonResponse('TODO!');
    }
}