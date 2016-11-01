<?php

namespace App\Controller;

use App\Entity\KeyCombo;
use App\Entity\KeyStroke;
use App\Entity\User;
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
            $startDateTime = \DateTime::createFromFormat('d.m.Y H:i:s', $item['time']['start']);
            $endDateTime = \DateTime::createFromFormat('d.m.Y H:i:s', $item['time']['end']);

            // Pre-format data and validate that we don't use something which is not set
            $item['keys'] = array_map('intval', isset($item['keys']) ? $item['keys'] : []);
            $item['keyCombos'] = isset($item['keyCombos']) ? $item['keyCombos'] : [];

            // Create keystroke entity
            $keyStroke = $this->createKeyStrokeEntity($item['keys'], $user, $startDateTime, $endDateTime);
            $this->manager()->persist($keyStroke);

            // Create key combo entities. There will be one entity per key combo.
            foreach ($item['keyCombos'] as $combo => $amount) {
                $keyCombo = $this->createKeyComboEntity($combo, $amount, $user, $startDateTime, $endDateTime);
                $this->manager()->persist($keyCombo);
            }
        }

        $this->manager()->flush();

        return new JsonResponse('OK');
    }

    /**
     * Creates the key stroke entity to be saved in to the database.
     *
     * @param array     $data
     * @param User      $user
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return KeyStroke
     */
    private function createKeyStrokeEntity(array $data, User $user, \DateTime $start, \DateTime $end)
    {
        /**
         * @var KeyStroke $keyStroke
         */
        $keyStroke = $this
            ->serializer
            ->deserialize(Json::encode($data), 'App\Entity\KeyStroke', 'json')
        ;

        $keyStroke->setUser($user);
        $keyStroke->setRequestedAt(new \DateTime());
        $keyStroke->setStartTime($start);
        $keyStroke->setEndTime($end);

        return $keyStroke;
    }

    /**
     * Creates a key combo entity.
     *
     * @param string    $combo
     * @param integer   $amount
     * @param User      $user
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return KeyCombo
     */
    private function createKeyComboEntity($combo, $amount, User $user, \DateTime $start, \DateTime $end)
    {
        $keyCombo = new KeyCombo();

        $keyCombo
            ->setCombo($combo)
            ->setAmount((int)$amount)
            ->setUser($user)
            ->setStartTime($start)
            ->setEndTime($end)
            ->setRequestedAt(new \DateTime())
        ;

        return $keyCombo;
    }
}
