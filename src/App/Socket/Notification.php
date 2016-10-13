<?php

namespace App\Socket;

use App\Entity\User;
use App\Util\Json;
use Doctrine\ORM\EntityManager;
use Namshi\JOSE\SimpleJWS;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/**
 * Notification socket for handling the real time notifications.
 *
 * @package App\Socket
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class Notification extends ContainerAwareSocket implements MessageComponentInterface
{
    /**
     * Container for the clients.
     *
     * @var array
     */
    protected $clients = [];

    /**
     * Method to be called when someone or something connects to the socket.
     *
     * @param ConnectionInterface $conn
     *
     * @return void
     */
    public function onOpen(ConnectionInterface $conn)
    {
        // TODO: make some note in to the logs
    }

    /**
     * Called when the message is retrieved from the client. This is also
     * called when the client authenticates by sending the JWT.
     *
     * @param ConnectionInterface $from
     * @param string $msg
     *
     * @return void
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        if ($data = Json::decode($msg)) { // We are sending message internally
            if ($this->validateSecret($data['secret'])) {
                $this->notify((int)$data['userId'], $data['data']);
            }
        } elseif ($user = $this->auth($msg)) {
            $this->clients[$from->resourceId] = [
                'user' => $user,
                'conn' => $from
            ];

            $from->send(
                Json::encode(
                    [
                        'from' => 'hillevi',
                        'tag' => 'REALTIME_CONNECTION_OPENED'
                    ]
                )
            );
        }
    }

    /**
     * Sends notification for the specific user.
     *
     * @param $userId
     * @param $msg
     */
    public function notify($userId, $msg)
    {
        /**
         * @var User $user
         * @var ConnectionInterface $conn
         */
        foreach ($this->clients as $client) {
            $user = $client['user'];
            $conn = $client['conn'];

            if ($user->getId() === $userId) {
                $conn->send(
                    Json::encode(
                        $msg
                    )
                );
            }
        }
    }

    /**
     * Method to be called when the connection is closed.
     *
     * @param ConnectionInterface $conn
     *
     * @return void
     */
    public function onClose(ConnectionInterface $conn)
    {
        unset($this->clients[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // TODO: make something in to the error logs
    }

    /**
     * Function to authenticate the user.
     *
     * @param   string  $jwt
     *
     * @return  bool
     */
    private function auth($jwt)
    {
        try {
            $jws = SimpleJWS::load($jwt);
        } catch (\InvalidArgumentException $e) {
            return false;
        }

        $doctrine = $this->container->get('doctrine');

        /**
         * @var EntityManager $em
         */
        $em = $doctrine->getManager();

        $user = $em->find('App:User', $jws->getPayload()['uid']);

        // We have to clear the caches
        $em->clear();

        if (!$user) {
            return false;
        }

        return $user->getToken() === $jwt ? $user : null;
    }

    /**
     * Validates that we are coming from the application
     * by using the secret we have defined in to the parameters.yml
     *
     * @param string $secret
     *
     * @return bool
     */
    public function validateSecret($secret)
    {
        return $secret === $this->container->getParameter('notification_secret');
    }
}
