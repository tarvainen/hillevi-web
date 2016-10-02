<?php

namespace App\DataFixtures\ORM;

use App\Entity\Settings;
use App\Entity\User;
use App\Naming\Right;
use App\Util\Password;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * User fixtures to load the default user data to the database.
 *
 * @package App\DataFixtures\Fixtures\ORM
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class UserFixture extends AbstractFixture implements OrderedFixtureInterface
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
        $admin->setPassword('admin');
        $admin->setFirstname('Administrator');
        $admin->setLastname('Admin');
        $admin->setSettings(new Settings());

        $settings = new Settings();
        $manager->persist($settings);

        $admin->setSettings($settings);

        $rights = $manager->getRepository('App:Permission')->findOneBy(
            [
                'name' => Right::ADMIN
            ]
        );

        $admin->getRights()->add($rights);

        $manager->persist($admin);
        $manager->flush();

        $this->setReference('admin-user', $admin);
    }

    /**
     * Returns the order number for the fixture execution.
     *
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
