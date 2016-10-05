<?php

namespace App\Command;

use App\Util\Logger;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Cron command to run all the cron scripts.
 *
 * @package App\Command
 */
class CronCommand extends ContainerAwareCommand
{
    /**
     * Configure the cron command.
     */
    public function configure()
    {
        $this->setName('cron:run');
    }

    /**
     * Execute the cron command.
     *
     * @param InputInterface    $input
     * @param OutputInterface   $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        Logger::log('cron runs!!');
    }
}
