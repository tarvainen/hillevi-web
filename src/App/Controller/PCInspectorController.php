<?php

namespace App\Controller;

use App\Entity\ActiveApplication;
use App\Entity\ComputerUsageSnapshot;
use App\Entity\KeyCombo;
use App\Entity\KeyStroke;
use App\Entity\MouseClick;
use App\Entity\MousePath;
use App\Entity\MousePosition;
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
            $item['mousePosition'] = isset($item['mousePosition']) ? $item['mousePosition'] : [];
            $item['screen'] = isset($item['screen']) ? $item['screen'] : [];
            $item['mouseClicks'] = isset($item['mouseClicks']) ? $item['mouseClicks'] : [];
            $item['mousePath'] = isset($item['mousePath']) ? $item['mousePath'] : '';
            $item['common'] = isset($item['common']) ? $item['common'] : [];

            // Create keystroke entity
            $keyStroke = $this->createKeyStrokeEntity($item['keys'], $user, $startDateTime, $endDateTime);
            $this->manager()->persist($keyStroke);

            $snapshot = $this->createComputerUsageSnapshotEntity($item['common'], $user, $startDateTime, $endDateTime);
            $this->manager()->persist($snapshot);

            // Create key combo entities. There will be one entity per key combo.
            foreach ($item['keyCombos'] as $combo => $amount) {
                $keyCombo = $this->createKeyComboEntity($combo, $amount, $user, $startDateTime, $endDateTime);
                $this->manager()->persist($keyCombo);
            }

            // Create mouse position entity
            $mousePosition = $this->createMousePositionEntity(
                [
                    'mouse' => $item['mousePosition'],
                    'screen' => $item['screen']
                ],
                $user,
                $startDateTime,
                $endDateTime
            );

            $this->manager()->persist($mousePosition);

            $mousePath = $this->createMousePathEntity(
                $item['mousePath'],
                $user,
                $startDateTime,
                $endDateTime
            );

            $this->manager()->persist($mousePath);

            // Create mouse click entities
            foreach ($item['mouseClicks'] as $click) {
                list($button, $x, $y) = explode(';', $click);
                $parts = [
                    'button' => $button,
                    'x' => $x,
                    'y' => $y
                ];

                $mouseClick = $this->createMouseClickEntity($parts, $user, $startDateTime, $endDateTime);
                $this->manager()->persist($mouseClick);
            }

            // Create active application entities
            foreach ($item['activeWindows'] as $window => $activeTime) {
                $activeApplication = $this->createActiveApplicationEntity(
                    [
                        'app' => $window,
                        'activeTime' => $activeTime
                    ],
                    $user,
                    $startDateTime,
                    $endDateTime
                );

                $this->manager()->persist($activeApplication);
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

    /**
     * Creates a mouse position entity.
     *
     * @param array     $data
     * @param User      $user
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return MousePosition
     */
    private function createMousePositionEntity($data, User $user, \DateTime $start, \DateTime $end)
    {
        $mousePosition = new MousePosition();

        $mousePosition
            ->setCoordinateX((int)$data['mouse']['x'])
            ->setCoordinateY((int)$data['mouse']['y'])
            ->setScreenHeight((int)$data['screen']['height'])
            ->setScreenWidth((int)$data['screen']['width'])
            ->setStartTime($start)
            ->setEndTime($end)
            ->setRequestedAt(new \DateTime())
            ->setUser($user)
        ;

        return $mousePosition;
    }

    /**
     * Create a mouse click entity.
     *
     * @param array     $data
     * @param User      $user
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return MouseClick
     */
    private function createMouseClickEntity($data, User $user, \DateTime $start, \DateTime $end)
    {
        $mouseClick = new MouseClick();

        $mouseClick
            ->setButton((int)$data['button'])
            ->setCoordinateX((int)$data['x'])
            ->setCoordinateY((int)$data['y'])
            ->setUser($user)
            ->setStartTime($start)
            ->setEndTime($end)
            ->setRequestedAt(new \DateTime())
        ;

        return $mouseClick;
    }

    /**
     * Creates a active application entity.
     *
     * @param array     $data
     * @param User      $user
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return ActiveApplication
     */
    private function createActiveApplicationEntity($data, User $user, \DateTime $start, \DateTime $end)
    {
        $activeApplication = new ActiveApplication();

        $activeApplication
            ->setName($data['app'])
            ->setActiveTime((int)$data['activeTime'])
            ->setStartTime($start)
            ->setEndTime($end)
            ->setRequestedAt(new \DateTime())
            ->setUser($user)
        ;

        return $activeApplication;
    }

    /**
     * Create the mouse path entity.
     *
     * @param   string    $data
     * @param   User      $user
     * @param   \DateTime $start
     * @param   \DateTime $end
     * @return  MousePath
     */
    private function createMousePathEntity($data, User $user, \DateTime $start, \DateTime $end)
    {
        $mousePathEntity = new MousePath();

        $mousePathEntity
            ->setStartTime($start)
            ->setEndTime($end)
            ->setUser($user)
            ->setPath($data)
        ;

        return $mousePathEntity;
    }

    /**
     * Create the computer usage snapshot entity.
     *
     * @param array      $data
     * @param User       $user
     * @param \DateTime  $start
     * @param \DateTime  $end
     *
     * @return ComputerUsageSnapshot
     */
    private function createComputerUsageSnapshotEntity($data, User $user, \DateTime $start, \DateTime $end)
    {
        $snapshot = new ComputerUsageSnapshot();

        $duration = ($end->format('U') - $start->format('U')) * 1000;
        $idle = $data['idleTime'];

        $snapshot
            ->setUser($user)
            ->setStartTime($start)
            ->setEndTime($end)
            ->setActiveUsage($duration - $idle);

        return $snapshot;
    }
}
