<?php

namespace App\Command;

use App\Util\Sql;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\DBAL\Connection;
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
            ->addArgument('table', InputArgument::REQUIRED, 'Table name')
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
        $tableName = $input->getArgument('table');

        $columns = $input->getArgument('columns');

        if (count($columns) <= 0) {
            $output->write('Define at least one column.');
        }

        $columns[] = 'id:auto';

        $columnClauses = array();
        
        foreach ($columns as $column) {
            list($title, $type) = explode(':', $column);

            $columnClauses[] = Sql::create($title, $type);
        }

        $sql = sprintf(
            '
              CREATE TABLE IF NOT EXISTS %1$s (
                %2$s
              );
            ',
            /** 1 */ $tableName,
            /** 2 */ implode(',', array_filter($columnClauses))
        );

        $doctrine = $this->getContainer()->get('doctrine');

        /**
         * @var Connection $conn
         */
        $conn = $doctrine
            ->getManager()
            ->getConnection();
        
        $conn->exec($sql);
    }
}