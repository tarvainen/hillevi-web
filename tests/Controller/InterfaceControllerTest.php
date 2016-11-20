<?php

namespace Test\Controller;
use App\Naming\ApiType;
use App\Test\ApiTestCase;
use App\Util\Json;
use App\Util\Sql;
use Symfony\Component\HttpFoundation\Response;


/**
 * Test cases for the auth controller.
 *
 * @package Test\Controller
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class InterfaceControllerTest extends ApiTestCase
{
    /**
     * Tests that the interface's test route works.
     */
    public function testInterfaceUrlTestStatusCodeAuthorized()
    {
        $response = $this
            ->route('/example/json/simple/int/0/10')
            ->auth('admin', 'admin')
            ->execute();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test that the interface's test route is available only for authorized user.
     */
    public function testInterfaceUrlTestStatusCodeUnauthorized()
    {
        $response = $this
            ->route('/example/json/simple/int/0/10')
            ->auth('admin', 'this_is_really_not_admin_password')
            ->execute();

        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * Test that the interface can be created and the status code is 200.
     *
     * @param array $param
     * @param int   $statusCode
     *
     * @dataProvider dataProviderCreatedInterfaces
     */
    public function testInterfaceCreationRouteStatusCode($param, $statusCode)
    {
        /** @var Response $response */
        $response = $this
            ->route('api/interface/create')
            ->parameters($param)
            ->auth('admin', 'admin')
            ->execute();

        $this->assertEquals($statusCode, $response->getStatusCode());

        // After creation just remove those interfaces
        if ($response->getStatusCode() === 200) {
            $this->checkInterfaceRemovalRouteStatusCode($response->getContent());
        }
    }

    /**
     * Test that the created interface exists in the database.
     *
     * @param $data
     */
    public function checkInterfaceRemovalRouteStatusCode($data)
    {
        if (empty($data)) {
            return;
        }

        $data = Json::decode($data);

        $response = $this
            ->route('api/interface/delete/' . $data['id'])
            ->auth('admin', 'admin')
            ->execute();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Provide data for the created interfaces.
     *
     * @return array
     */
    public function dataProviderCreatedInterfaces()
    {
        return [
            [
                [
                    'name'      => 'Test Interface 1',
                    'type'      => ApiType::JSON,
                    'url'       => 'this_is_not_a_real_url.not.real.domain',
                    'interval'  => 10,
                    'unit'      => 'kpl',
                    'aggregate' => Sql::AGGREGATE_AVERAGE
                ], 200 // Everything ok
            ],
            [
                [
                    'name'      => 'Failing Interface 1',
                    'url'       => 'this_is_not_a_real_url.not.real.domain',
                    'interval'  => 10,
                ], 500 // Not enough parameters
            ],
        ];
    }
}