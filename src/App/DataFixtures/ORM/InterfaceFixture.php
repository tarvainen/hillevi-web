<?php

namespace App\DataFixtures\ORM;

use App\Entity\ApiReader;
use App\Naming\FieldType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * The interface fixtures to be loaded to the database by default.
 *
 * @package App\DataFixtures\ORM
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class InterfaceFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load the data to the database.
     *
     * @param ObjectManager $manager
     *
     * @return  void
     */
    public function load(ObjectManager $manager)
    {
        $api = new ApiReader();
        $api->setName('DemoApi')
            ->setLastUpdate(new \DateTime())
            ->setTableName('demo_api_table')
            ->setType('rest')
            ->setActive(true)
            ->setLastRun(new \DateTime())
            ->setUrl('https://myexampleapiswhichdoesntreadllyexists.com/temperature/now')
            ->setColumns([
                [
                    'field' => 'Testfield',
                    'type' => FieldType::DECIMAL
                ]
            ])
            ->setOwner($this->getReference('admin-user'));

        $manager->persist($api);
        $manager->flush();
    }

    /**
     * Gets the order for the fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}