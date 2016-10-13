<?php

namespace App\Notification;

use App\Util\Json;
use Ratchet\Client;
use Ratchet\ConnectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Static notifier for making notification more simple to use.
 *
 * @package App\Notification
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class Notifier
{
    /**
     * @var null|ConnectionInterface
     */
    protected static $connection = null;

    /**
     * @var string
     */
    protected static $secret = '';

    /**
     * Connects the to the socket.
     *
     * @param ContainerInterface $container
     * @param callable|null $onConnect
     * @param callable|null $onFail
     *
     * @return void
     */
    public static function connect(ContainerInterface $container, callable $onConnect = null, callable $onFail = null)
    {
        $port = $container->getParameter('web_socket_port');
        self::$secret = $container->getParameter('notification_secret');

        $onConnection = function ($conn) use ($onConnect) {
            self::$connection = $conn;
            call_user_func($onConnect);
        };

        $onReject = function () use ($onFail) {
            $onFail();
        };

        Client\connect('ws://0.0.0.0:' . $port)
            ->then($onConnection, $onReject);
    }

    /**
     * Closes the socket connection.
     *
     * @return void
     */
    public static function disconnect()
    {
        if (is_null(self::$connection)) {
            return;
        }

        self::$connection->close();
    }

    /**
     * Sends notification for the user.
     *
     * @param int   $userId
     * @param mixed $data
     *
     * @return void
     */
    public static function notify($userId, $data)
    {
        if (is_null(self::$connection)) {
            return;
        }

        self::$connection->send(
            Json::encode(
                [
                    'secret' => self::$secret,
                    'userId' => $userId,
                    'data' => $data
                ]
            )
        );
    }
}