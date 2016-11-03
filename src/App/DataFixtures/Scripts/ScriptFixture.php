<?php

namespace App\DataFixtures\Scripts;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Finder\Finder;

/**
 * Run all the database scripts to the database so it will be ready to use.
 *
 * This script reads all the *.sql -files from the src/Sql/ directory and
 * executes their contents in to the database.
 *
 * @package App\DataFixtures\Scripts\ScriptFixture
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class ScriptFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Loads the admin user in to the database by default.
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        /**
         * @var EntityManager $manager
         */
        $conn = $manager->getConnection();

        $finder = new Finder();

        echo "* Starts running database initialization scripts from /src/Sql *\n";

        $finder->files()->in('src/Sql/');

        foreach ($finder as $file) {
            if ($file->getExtension() !== 'sql') {
                continue;
            }

            echo sprintf(
                "Running script %1\$s ... ",
                /** 1 */ $file->getFilename()
            );

            $conn->query($file->getContents());

            echo "done \n";
        }

        echo "* All scripts initiated to the database successfully. *\n\n";
    }

    /**
     * Returns the order number for the fixture execution.
     *
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }
}
