<?php

namespace App\Command;

use App\Entity\ApiReader;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to create interface (the table).
 *
 * @package App\Command
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class CreateInterfaceCommand extends ContainerAwareCommand
{
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('app:interface:create')
            ->setDescription('Create the interface.')
            ->addArgument('name', InputArgument::REQUIRED, 'Api name')
            ->addArgument('type', InputArgument::REQUIRED, 'API type like json or xml')
            ->addArgument('url', InputArgument::REQUIRED, 'API url')
            ->addArgument('columns', InputArgument::IS_ARRAY, 'Gimme the schema!');
    }

    /**
     * Executes the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $columns = $input->getArgument('columns');

        if (count($columns) <= 0) {
            $output->writeln('Define at least one column!');
            die();
        }

        $columns[] = 'id:auto';

        $api = new ApiReader();

        $api->setName($name);
        $api->setTableName(strtolower(preg_replace('/((?![A-Za-z]).)/', '', $name)));
        $api->setType($input->getArgument('type'));
        $api->setUrl($input->getArgument('url'));
        $api->setColumns(array());
        $api->setLastUpdate(new \DateTime());

        $doctrine = $this->getContainer()->get('doctrine');

        /**
         * @var EntityManager $em
         */
        $em = $doctrine->getManager();

        $em->persist($api);
        $em->flush();
    }
}