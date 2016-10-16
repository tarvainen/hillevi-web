<?php

namespace App\DataFixtures\Sample;

use App\Entity\ApiReader;
use App\Entity\User;
use App\Naming\FieldType;
use App\Util\FieldFormatter;
use App\Util\Random;
use App\Util\Sql;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

/**
 * The sample data fixtures to be loaded to the database by default.
 *
 * @package App\DataFixtures\ORM
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class SampleDataFixture extends AbstractFixture implements OrderedFixtureInterface
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
        /**
         * @var User $admin
         */
        $admin = $manager
            ->getRepository('App:User')
            ->findOneBy(['username' => 'admin'])
        ;

        $definitions = [
            [
                'user' => $admin,
                'api' => 'Simple Random Integer JSON',
                'type' => 'rest',
                'interval' => 3600,
                'columns' => [
                    [
                        'name' => 'Random int',
                        'field' => 'value',
                        'type' => FieldType::INTEGER
                    ]
                ],
                'data' => [
                    'rows' => 1000,
                    'min' => 5,
                    'max' => 50,
                    'startAt' => '2013-01-01'
                ]
            ],
            [
                'user' => $admin,
                'api' => 'Simple Random Integer Larger JSON',
                'type' => 'rest',
                'interval' => 3600,
                'columns' => [
                    [
                        'name' => 'Random int',
                        'field' => 'value',
                        'type' => FieldType::INTEGER
                    ]
                ],
                'data' => [
                    'rows' => 1000,
                    'min' => 100,
                    'max' => 200,
                    'startAt' => '2013-01-01'
                ]
            ]
        ];

        foreach ($definitions as $definition) {
            $this->createFixtureTableAndData($manager, $definition);
        }
    }

    /**
     * Gets the order for the fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }

    /**
     * Create fixture data from definition.
     *
     * @param EntityManager $manager
     * @param array $definition
     *
     * @return void
     */
    private function createFixtureTableAndData(EntityManager $manager, $definition)
    {
        $apiName = $definition['api'];

        $api = new ApiReader();
        $api->setName($apiName)
            ->setLastUpdate(new \DateTime())
            ->setType($definition['type'])
            ->setActive(true)
            ->setTableName(
                FieldFormatter::toTableNameFormat('user_' . $definition['user']->getUsername() . '_def_' . $apiName)
            )
            ->setLastRun(new \DateTime())
            ->setUrl('/example/json/simple/integer/' . $definition['data']['min'] . '/' . $definition['data']['max'])
            ->setColumns($definition['columns'])
            ->setInterval($definition['interval'])
            ->setOwner($definition['user']);

        $manager->persist($api);
        $manager->flush();

        // The API's table creation SQL
        $sql = sprintf(
            '
              CREATE TABLE %1$s (
                ID INT(11) AUTO_INCREMENT PRIMARY KEY,
                REQUESTED_AT DATETIME
              );
            ',
            /** 1 */ $api->getTableName()
        );

        $manager
            ->getConnection()
            ->query($sql)
        ;

        $this->updateInterfaceColumns($api->getTableName(), [], $definition['columns'], $manager);

        $insertSql = 'START TRANSACTION;';

        for ($i = 0; $i < $definition['data']['rows']; $i++) {
            $insertSql .= sprintf(
                'INSERT INTO %1$s (REQUESTED_AT, value) VALUES ("%2$s" + INTERVAL %3$s DAY, %4$s);',
                /** 1 */ $api->getTableName(),
                /** 2 */ $definition['data']['startAt'],
                /** 3 */ $i,
                /** 4 */ Random::integer($definition['data']['min'], $definition['data']['max'])
            );
        }

        $insertSql .= 'COMMIT;';

        $manager->getConnection()->query($insertSql);
    }

    /**
     * Updates the table's columns using the old column array and the new column array
     * as reference to check if there is some changes.
     *
     * @param string $table
     * @param array  $oldColumns
     * @param array  $newColumns
     * @param EntityManager $manager
     *
     * @return void
     */
    private function updateInterfaceColumns($table, $oldColumns, $newColumns, $manager)
    {
        $oldColumns = $oldColumns ?? [];
        $newColumns = $newColumns ?? [];

        $removedColumns = [];
        $changedColumns = [];
        $addedColumns = [];

        foreach ($newColumns as $uniqid => $column) {
            if (isset($oldColumns[$uniqid]) &&
                (
                    $oldColumns[$uniqid]['field'] !== $column['field'] ||
                    $oldColumns[$uniqid]['type'] !== $column['type']
                )
            ) { // changed column
                $column['oldField'] = $oldColumns[$uniqid]['field'];
                $changedColumns[$uniqid] = $column;
            } elseif (!array_key_exists($uniqid, $oldColumns)) { // new column
                $addedColumns[$uniqid] = $column;
            }
        }

        foreach ($oldColumns as $uniqid => $column) {
            if (!array_key_exists($uniqid, $newColumns)) { // removed column
                $removedColumns[$uniqid] = $column;
            }
        }

        $alterSqls = [];

        foreach ($removedColumns as $uniqid => $column) {
            $alterSqls[] = sprintf(
                'DROP COLUMN %1$s',
                /** 1 */ $column['field']
            );
        }

        foreach ($addedColumns as $uniqid => $column) {
            $alterSqls[] = sprintf(
                'ADD COLUMN %1$s %2$s',
                /** 1 */ $column['field'],
                /** 2 */ Sql::$dbTypes[$column['type']]
            );
        }

        foreach ($changedColumns as $uniqid => $column) {
            $alterSqls[] = sprintf(
                'CHANGE %1$s %2$s %3$s',
                /** 1 */ $column['oldField'],
                /** 2 */ $column['field'],
                /** 3 */ Sql::$dbTypes[$column['type']]
            );
        }

        if (empty($alterSqls)) {
            return ;
        }

        $sql = sprintf(
            'ALTER TABLE %1$s %2$s',
            /** 1 */ $table,
            /** 2 */ implode(',', $alterSqls)
        );

        $manager->getConnection()->query($sql);
    }
}
