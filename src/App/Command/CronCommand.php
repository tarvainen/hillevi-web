<?php

namespace App\Command;

use App\Entity\ApiReader;
use App\Reader\JsonInterfaceReader;
use Doctrine\ORM\EntityManager;
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
        $doctrine = $this->getContainer()->get('doctrine');

        /**
         * @var EntityManager $em
         */
        $em = $doctrine->getManager();

        $apis = $em->getRepository('App:ApiReader')
            ->createQueryBuilder('a')
            ->where(
                '
                    a.active = 1 AND 
                    DATE_ADD(a.lastRun, (a.interval * 60), \'second\') < CURRENT_TIMESTAMP() AND 
                    a.type != \'inner\'
                '
            )
            ->getQuery()
            ->getResult();

        foreach ($apis as $api) {
            /** @var ApiReader $api */
            // TODO: check api type
            $reader = new JsonInterfaceReader();
            $reader->setUrl($api->getUrl());

            $reader->setColumns($api->getColumns());

            $data = $reader->execute();
            $data['REQUESTED_AT'] = date('Y-m-d H:i:s');

            $bindings = [];

            foreach ($data as $key => $value) {
                $bindings[':' . $key] = $value;
            }

            $sql = sprintf(
                'INSERT INTO %1$s (%2$s) VALUES(%3$s)',
                /** 1 */
                $api->getTableName(),
                /** 2 */
                implode(',', array_keys($data)),
                /** 3 */
                implode(',', array_keys($bindings))
            );

            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute($bindings);

            $api->setLastRun(new \DateTime());
            $em->persist($api);
            $em->flush();
        }
    }
}
