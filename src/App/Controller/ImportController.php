<?php

namespace App\Controller;

use App\Entity\ApiReader;
use App\Exception\ActionFailedException;
use App\Naming\FieldType;
use App\Util\Arrays;
use App\Util\Sql;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for data imports.
 *
 * @package App\Controller
 *
 * @Route("import/")
 * @Method("POST")
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class ImportController extends CController
{
    /**
     * Action for importing data to specific api.
     *
     * @Permission("import:execute")
     *
     * @param string  $id
     * @param string  $token
     * @param Request $request
     *
     * @Route("{id}/{token}")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function importDataAction($id, $token, Request $request)
    {
        /** @var ApiReader $api */
        $api = $this
            ->manager()
            ->getRepository('App:ApiReader')
            ->findOneBy(
                [
                    'id' => (int)$id,
                    'token' => $token
                ]
            );

        $data = $request->get('data');

        if (!$api || !Arrays::isMultidimensionalArray($data)) {
            throw new ActionFailedException('import');
        }

        $sql = 'START TRANSACTION;';

        $bindings = [];

        $index = 0;

        foreach ($data as $row) {
            $row['REQUESTED_AT'] = date('Y-m-d H:i:s');
            $columns = $api->getColumns();

            $columns['timestamp'] = [
                'field' => 'REQUESTED_AT',
                'type' => FieldType::STRING
            ];

            list($insert, $params) = Sql::insert($row, $api->getTableName(), $columns, $index);

            $sql .= $insert;
            $bindings = array_merge($bindings, $params);

            $index++;
        }

        $sql .= 'COMMIT;';

        /**
         * @var Connection $conn
         */
        $conn = $this->manager()->getConnection();
        $stmt = $conn->prepare($sql);

        if ($success = $stmt->execute($bindings)) {
            return new JsonResponse('OK');
        }

        throw new ActionFailedException('import');
    }
}
