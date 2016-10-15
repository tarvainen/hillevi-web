<?php

namespace App\Command;

use App\Controller\InterfaceController;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * A non-doing command for testing different stuff.
 *
 * @package App\Command
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class PlaygroundCommand extends ContainerAwareCommand
{
    /**
     * Configure the command.
     *
     * @return  void
     */
    public function configure()
    {
        $this->setName('playground');
    }

    /**
     * Runs when the command is executed.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
