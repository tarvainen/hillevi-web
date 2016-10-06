<?php

namespace App\Controller;

use App\Entity\ApiReader;
use App\Exception\ActionFailedException;
use App\Exception\NotFoundException;
use App\Naming\ApiType;
use App\Naming\FieldType;
use App\Util\FieldFormatter;
use App\Util\Sql;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * Returns a list of all available interfaces for current user.
     *
     * @Permission("perm=interface")
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
            ->select('partial a.{id, name, type, url, columns, lastUpdate, active, interval}')
            ->where('a.owner = :id')
            ->setParameter(':id', $this->getUser()['uid'])
            ->getQuery();

        return new JsonResponse($query->getArrayResult());
    }

    /**
     * The route for creating new interface.
     *
     * @Permission("perm=interface:create")
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
        $data = $this->mapHashFromRequest(['name', ' type', 'url', 'type', 'interval']);

        $data['tableName'] = FieldFormatter::toTableNameFormat(
            'user_' . $this->getUserEntity()->getUsername() . '_def_' . $data['name']
        );

        $data['active'] = true;

        $api = new ApiReader();
        $api->fromArray($data);

        $api->setOwner($this->getUserEntity());

        $demoColumn = [
            md5(microtime()) => [
                'field' => 'DEMO',
                'type' => FieldType::INTEGER
            ]
        ];

        $api->setColumns($demoColumn);

        $api->setLastUpdate(new \DateTime());
        $api->setLastRun(new \DateTime());

        $em = $this->getDoctrine()->getManager();

        $em->persist($api);

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

        $em->flush();

        if (!$em->contains($api)) {
            throw new ActionFailedException('save');
        }

        return new JsonResponse('OK');
    }

    /**
     * Route to delete an interface.
     *
     * @Permission("perm=interface:delete")
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
            '
              DROP TABLE IF EXISTS %1$s
            ',
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
     * @Permission("perm=interface:create")
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

        $data = $this->mapHashFromRequest(['name', 'type', 'url', 'columns', 'interval']);
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
     * Action to fetch all possible interface field types.
     *
     * @Route("fields/types")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getInterfaceFieldTypesAction()
    {
        $types = [
            FieldType::INTEGER,
            FieldType::DECIMAL,
            FieldType::STRING
        ];

        return new JsonResponse($types);
    }

    /**
     * Action to fetch all possible interface types.
     *
     * @Route("types")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getInterfaceTypesAction()
    {
        $types = [
            ApiType::INNER,
            ApiType::JSON,
            ApiType::XML
        ];

        return new JsonResponse($types);
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

        $this->getDoctrine()->getConnection()->query($sql);
    }
}
