<?php

namespace App\Controller;

use App\Entity\ApiReader;
use App\Exception\ActionFailedException;
use App\Exception\NotFoundException;
use App\Naming\ApiType;
use App\Naming\FieldType;
use App\Util\Curl;
use App\Util\FieldFormatter;
use App\Util\Json;
use App\Util\Sql;
use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Annotation\Permission;

/**
 * Controller to handle interfaces.
 *
 * @package App\Controller
 *
 * @Route("api/interface/")
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class InterfaceController extends CController
{
    /**
     * Action to test if the given url is working or not.
     *
     * @Permission("interface:own:write")
     *
     * @Route("test")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function testAction(Request $request)
    {
        $data = Curl::read($request->get('url', ''));

        return new JsonResponse(Json::decode($data));
    }

    /**
     * Returns a list of all available interfaces for current user.
     *
     * @Permission("interface:own:read")
     *
     * @Route("all")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getAllInterfaceAction()
    {
        $query = $this
            ->getDoctrine()
            ->getEntityManager()
            ->createQueryBuilder()
            ->from('App:ApiReader', 'a')
            ->select('partial a.{id, name, type, url, columns, lastUpdate, active, interval, token}')
            ->where('a.owner = :id')
            ->setParameter(':id', $this->getUser()['uid'])
            ->getQuery();

        return new JsonResponse($query->getArrayResult());
    }

    /**
     * The route for creating new interface.
     *
     * @Permission("interface:own:write")
     *
     * @Route("create")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addInterfaceAction(Request $request)
    {
        $data = $this->mapHashFromRequest(['name', ' type', 'url', 'type', 'interval', 'aggregate', 'unit']);

        $data['tableName'] = FieldFormatter::toTableNameFormat(
            'user_' . $this->getUserEntity()->getUsername() . '_def_' . $data['name']
        );

        $data['active'] = true;

        $api = new ApiReader();
        $api->fromArray($data);

        $api->setOwner($this->getUserEntity());
        $api->refreshToken();

        $demoColumn = [
            md5(microtime()) => [
                'name' => 'DemoField',
                'field' => 'value',
                'type' => FieldType::INTEGER,
                'aggregate' => Sql::AGGREGATE_SUM,
                'unit' => 'kpl',
            ]
        ];

        $api->setColumns($demoColumn);

        $api->setLastUpdate(new \DateTime());
        $api->setLastRun(new \DateTime());

        $em = $this->getDoctrine()->getManager();

        $em->persist($api);

        $em->flush();

        if (!$em->contains($api)) {
            throw new ActionFailedException('save');
        }

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

        $this->getDoctrine()
            ->getConnection()
            ->query($sql)
        ;

        $this->updateInterfaceColumns($api->getTableName(), [], $demoColumn);

        return new JsonResponse('OK');
    }

    /**
     * Route to delete an interface.
     *
     * @Permission("interface:own:write")
     *
     * @Route("delete/{id}")
     * @Method("POST")
     *
     * @param int $id
     *
     * @throws NotFoundException
     *
     * @return JsonResponse
     */
    public function removeInterfaceAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $api = $em->find('App:ApiReader', $id);

        if (!$api) {
            throw new NotFoundException('interface');
        }

        $em->remove($api);

        $sql = sprintf(
            'DROP TABLE IF EXISTS %1$s',
            /** 1 */ $api->getTableName()
        );

        $this->getDoctrine()
            ->getConnection()
            ->query($sql)
        ;

        $em->flush();

        return new JsonResponse('OK');
    }

    /**
     * Route for updating interfaces information.
     *
     * @Permission("interface:own:write")
     *
     * @Route("update", requirements={"id": "\d+"})
     * @Method("POST")
     */
    public function updateInterfaceAction(Request $request)
    {
        $id = $request->get('id');

        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();

        /**
         * @var ApiReader $api
         */
        $api = $em->find('App:ApiReader', $id);

        // The old columns
        $columns = $api->getColumns();

        $data = $this->mapHashFromRequest(['name', 'type', 'url', 'columns', 'interval', 'aggregate', 'unit']);
        $api->fromArray($data);

        /**
         * Update the interface table's columns.
         */
        $this->updateInterfaceColumns(
            $api->getTableName(),
            $columns,
            $api->getColumns()
        );

        $em->persist($api);
        $em->flush();

        return new JsonResponse('OK');
    }

    /**
     * Route for fetching data from the specified interface.
     *
     * @param Request $request
     *
     * @Permission("interface:own:read")
     *
     * @Route("data")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getDataAction(Request $request)
    {
        list($id) = $this->mapFromRequest(['id']);

        $em = $this->getDoctrine()->getManager();

        /**
         * @var ApiReader $api
         */
        $api = $em
            ->getRepository('App:ApiReader')
            ->findOneBy([
                'id' => (int)$id,
                'owner' => $this->getUserEntity()->getId()
            ]);

        $sql = sprintf(
            '
              SELECT *
              FROM %1$s x
              ORDER BY x.REQUESTED_AT
            ',
            /** 1 */ $api->getTableName()
        );

        /**
         * @var PDOStatement $stmt
         */
        $stmt = $this->getDoctrine()->getConnection()->query($sql);

        return new JsonResponse($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Route for getting the schema of the api table.
     *
     * @param Request $request
     *
     * @Permission("interface:own:read")
     *
     * @Route("schema")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getSchemaAction(Request $request)
    {
        $id = $request->get('id', -1);

        $em = $this->getDoctrine()->getManager();

        /**
         * @var ApiReader $api
         */
        $api = $em->find('App:ApiReader', $id);

        if (!$api) {
            throw new NotFoundException('ApiReader');
        }

        return new JsonResponse($api->getColumns());
    }

    /**
     * A route for removing api rows.
     *
     * @Permission("interface:own:write")
     *
     * @Route("data/rows/remove")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function removeRowsAction(Request $request)
    {
        list($id, $rowIds) = $this->mapFromRequest(['id', 'rows']);

        $rows = explode(',', $rowIds);
        $rows = array_map('intval', $rows);

        $em = $this->getDoctrine()->getManager();

        /**
         * @var ApiReader $api
         */
        $api = $em->find('App:ApiReader', $id);

        if (!$api) {
            throw new NotFoundException('ApiReader');
        }

        $sql = sprintf(
            '
              DELETE FROM
                %1$s
              WHERE
                ID IN (%2$s)
            ',
            /** 1 */ $api->getTableName(),
            /** 2 */ implode(',', $rows)
        );

        $this->getDoctrine()->getConnection()->query($sql);

        return new JsonResponse('OK');
    }

    /**
     * A route for adding api rows.
     *
     * @Permission("interface:own:write")
     *
     * @Route("data/rows/add")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addRowAction(Request $request)
    {
        list($apiId, $data) = $this->mapFromRequest(['api', 'data']);

        /**
         * @var ApiReader $api
         */
        $api = $this
            ->manager()
            ->getRepository('App:ApiReader')
            ->findOneBy([
                'id' => $apiId,
                'owner' => $this->getUserEntity()->getId()
            ]);

        if (!$api) {
            throw new ActionFailedException('save');
        }

        $values = $this->validateSavedRowForSchema($api->getColumns(), $data);
        $values['REQUESTED_AT'] = date('Y-m-d H:i:s');

        $parametrize = function ($key) {
            return ':' . $key;
        };

        $sql = sprintf(
            'INSERT INTO %1$s (%2$s) VALUES (%3$s)',
            /** 1 */ $api->getTableName(),
            /** 2 */ implode(',', array_keys($values)),
            /** 3 */ implode(',', array_map($parametrize, array_keys($values)))
        );

        $bindings = [];

        foreach ($values as $key => $value) {
            $bindings[':' . $key] = $value;
        }

        /**
         * @var PDOStatement $stmt
         */
        $stmt = $this->getDoctrine()->getConnection()->prepare($sql);
        $stmt->execute($bindings);

        return new JsonResponse('OK');
    }

    /**
     * Updates the table's columns using the old column array and the new column array
     * as reference to check if there is some changes.
     *
     * @param string $table
     * @param array  $oldColumns
     * @param array  $newColumns
     *
     * @return void
     */
    private function updateInterfaceColumns($table, $oldColumns, $newColumns)
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
                /** 1 */ FieldFormatter::toTableNameFormat($column['field'])
            );
        }

        foreach ($addedColumns as $uniqid => $column) {
            $alterSqls[] = sprintf(
                'ADD COLUMN %1$s %2$s',
                /** 1 */ FieldFormatter::toTableNameFormat($column['field']),
                /** 2 */ Sql::$dbTypes[$column['type']]
            );
        }

        foreach ($changedColumns as $uniqid => $column) {
            $alterSqls[] = sprintf(
                'CHANGE %1$s %2$s %3$s',
                /** 1 */ FieldFormatter::toTableNameFormat($column['oldField']),
                /** 2 */ FieldFormatter::toTableNameFormat($column['field']),
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

        $this->getDoctrine()->getConnection()->query($sql);
    }

    /**
     * Validates the data pushed to the database so there won't be any harmful content.
     *
     * @param array $schema
     * @param array $row
     *
     * @return array
     */
    private function validateSavedRowForSchema(array $schema, array $row)
    {
        $result = [];

        foreach ($row as $key => $value) {
            if (array_key_exists($key, $schema)) {
                $field = FieldFormatter::toTableNameFormat($schema[$key]['field']);
                $result[$field] = FieldFormatter::format($value, $schema[$key]['type']);
            }
        }

        return $result;
    }
}
