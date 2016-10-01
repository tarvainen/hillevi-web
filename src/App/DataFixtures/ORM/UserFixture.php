<?php

namespace App\DataFixtures\ORM;

use App\Entity\User;
use App\Util\Password;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * User fixtures to load the default user data to the database.
 *
 * @package App\DataFixtures\Fixtures\ORM
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class UserFixture implements FixtureInterface
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
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword(Password::hash('admin'));
        $admin->setFirstname('Administrator');
        $admin->setLastname('Admin');

        $manager->persist($admin);
        $manager->flush();
    }
}
