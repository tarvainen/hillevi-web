<?php

namespace App\Socket;

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
     * @var \SplObjectStorage
     */
    protected $clients;

    /**
     * Notification constructor.
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function __construct($container) {
        parent::__construct($container);

        $this->clients = new \SplObjectStorage;
    }

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
        if ($this->auth($msg)) {
            $this->clients->attach($from);
            $from->send('REALTIME_CONNECTION_OPENED');
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
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // TODO: make something in to the error logs
    }

    /**
     * Function to authenticate the user.
     *
     * @param   string  $jwt
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

        return $user->getToken() === $jwt;
    }
}