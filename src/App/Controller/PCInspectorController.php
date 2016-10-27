<?php

namespace App\Controller;

use App\Entity\KeyStroke;
use App\Exception\ActionFailedException;
use App\Util\Json;
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

    /**
     * Action for pushing inspection data.
     *
     * @param  Request $request
     *
     * @Route("push")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function pushDataAction(Request $request)
    {
        $token = $request->headers->get('authorization');

        if (!$token) {
            throw new ActionFailedException('auth');
        }

        $user = $this
            ->manager()
            ->getRepository('App:User')
            ->findOneBy(
                [
                    'apiKey' => $token
                ]
            );

        if (!$user) {
            throw new ActionFailedException('auth');
        }

        $data = $request->get('data', []);

        // The data may contain multiple read arrays of data. Loop them!
        foreach ($data as $item) {
            $item['keys'] = array_map('intval', $item['keys']);

            /**
             * @var KeyStroke $keyStroke
             */
            $keyStroke = $this
                ->serializer
                ->deserialize(Json::encode($item['keys']), 'App\Entity\KeyStroke', 'json')
            ;

            $keyStroke->setUser($user);
            $keyStroke->setRequestedAt(
                \DateTime::createFromFormat('d.m.Y H:i:s', $item['timestamp'])
            );

            $this->manager()->persist($keyStroke);
        }

        $this->manager()->flush();

        return new JsonResponse('OK');
    }
}
