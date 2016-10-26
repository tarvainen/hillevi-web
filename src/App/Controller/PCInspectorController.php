<?php

namespace App\Controller;

use App\Exception\ActionFailedException;
use App\Util\Logger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for the Hillevi PC inspector add-on.
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 *
 * @package App\Controller
 *
 * @Route("/api/mod/pcinspect/")
 * @Method("POST")
 */
class PCInspectorController extends CController
{
    /**
     * Action for the connection test.
     *
     * @Route("test")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function testConnectionAction(Request $request)
    {
        $token = $request->headers->get('authorization');

        if (!$token) {
            throw new ActionFailedException('auth');
        }

        return new JsonResponse('OK');
    }
}
