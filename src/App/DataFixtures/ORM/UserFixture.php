<?php

namespace App\DataFixtures\ORM;

use App\Entity\Settings;
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
        $admin->setSettings(new Settings());

        $settings = new Settings();
        $manager->persist($settings);

        $admin->setSettings($settings);

        $manager->persist($admin);

        $manager->flush();
    }
}
