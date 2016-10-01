<?php

namespace App\DataFixtures\ORM;

use App\Entity\Permission;
use App\Naming\Right;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Permission data fixtures to be preloaded to the database in the initialization.
 *
 * @package App\DataFixtures\ORM
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class PermissionFixture implements FixtureInterface
{
    /**
     * Loads permissions data in to the database.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // Define all loaded right modules here
        $rights = [
            Right::ADMIN,
            Right::DASHBOARD,
            Right::USER_SETTINGS,
        ];

        foreach ($rights as $right) {
            $entity = new Permission();
            $entity->setName($right);

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
