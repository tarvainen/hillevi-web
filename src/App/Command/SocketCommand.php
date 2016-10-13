<?php

namespace App\Command;

use App\Socket\Notification;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * A command to start listening the socket.
 *
 * @package App\Command
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class SocketCommand extends ContainerAwareCommand
{
    /**
     * Configure the command.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('socket:start');
    }

    /**
     * The command executor.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $notification = new Notification($this->getContainer());

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    $notification
                )
            ),
            $this->getContainer()->getParameter('web_socket_port')
        );

        $server->run();
    }
}
