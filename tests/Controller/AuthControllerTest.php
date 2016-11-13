<?php

namespace Test\Controller;

use App\Test\ApiTestCase;
use App\Util\Json;
use Namshi\JOSE\SimpleJWS;

/**
 * Test cases for the auth controller.
 *
 * @package Test\Controller
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class AuthControllerTest extends ApiTestCase
{
    /**
     * Test for the login action
     *
     * @dataProvider providerValidUsers
     */
    public function testLoginSuccessStatusCode($username, $password)
    {
        $response = $this
            ->route('/api/auth/login')
            ->parameters([
                'username' => $username,
                'password' => $password
            ])
            ->execute();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test to test that login route fails on wrong credentials.
     *
     * @param string $username
     * @param string $password
     *
     * @dataProvider providerInvalidUsers
     */
    public function testLoginFailStatusCode($username, $password)
    {
        $response = $this
            ->route('/api/auth/login')
            ->parameters([
                'username' => $username,
                'password' => $password
            ])
            ->execute();

        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * Test that the JWT returned from the REST is valid.
     *
     * @param string $username
     * @param string $password
     *
     * @dataProvider providerValidUsers
     */
    public function testLoginSuccessValidJWT($username, $password)
    {
        try {
            $jws = SimpleJWS::load($this->getAuthToken($username, $password));
        } catch (\InvalidArgumentException $e) {
            $jws = null;
        }

        $this->assertTrue(!is_null($jws));
    }

    /**
     * Test that me action returns a user when the user is authorized with the JWT.
     *
     * @param string $username
     * @param string $password
     *
     * @dataProvider providerValidUsers
     */
    public function testMeActionSuccess($username, $password)
    {
        $jwt = $this->getAuthToken($username, $password);

        $user = Json::decode(
            $this
                ->route('/api/auth/me')
                ->headers([
                        'HTTP_AUTHORIZATION' => $jwt
                ])
                ->execute()
                ->getContent()
        );

        $this->assertEquals($username, $user['username']);
    }

    /**
     * Test that the api/auth/me returns 401 - Unauthorized if no JWT is specified.
     *
     * @param  string $username
     * @param  string $password
     *
     * @dataProvider providerInvalidUsers
     */
    public function testMeActionUnauthorized($username, $password)
    {
        $response = $this
            ->route('/api/auth/me')
            ->headers([
                'HTTP_AUTHORIZATION' => md5(rand(0, 100) . $username . $password)
            ])
            ->execute();

        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * Test that password can be changed successfully.
     *
     * @param string $username
     * @param string $oldPassword
     * @param string $newPassword
     *
     * @dataProvider providerValidPasswordChange
     */
    public function testPasswordChangeActionSuccess($username, $oldPassword, $newPassword)
    {
        $jwt = $this->getAuthToken($username, $oldPassword);

        $response = $this
            ->route('/api/auth/settings/password/change')
            ->parameters([
                'oldPassword' => $oldPassword,
                'newPassword' => $newPassword,
                'newPasswordAgain' => $newPassword
            ])
            ->headers([
                'HTTP_AUTHORIZATION' => $jwt
            ])
            ->execute();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test that the logout works and removes the jwt from the database too.
     *
     * @param string $username
     * @param string $password
     *
     * @dataProvider providerValidUsers
     */
    public function testLogout($username, $password)
    {
        $jwt = $this->getAuthToken($username, $password);

        $headers = [
            'HTTP_AUTHORIZATION' => $jwt
        ];

        // Logout
        $this
            ->route('/api/auth/logout')
            ->headers($headers)
            ->execute();

        $response = $this
            ->route('/api/auth/me')
            ->headers($headers)
            ->execute();

        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * Data provider for the login succession test.
     *
     * @return array
     */
    public function providerValidUsers()
    {
        return [
            ['admin', 'admin']
        ];
    }

    /**
     * Data provider for the login fail test.
     *
     * @return array
     */
    public function providerInvalidUsers()
    {
        return [
            ['fail1', 'fail2'],
            ['admin', 'fail1'],
            ['admi', ''],
            [null, null],
            ['', '']
        ];
    }

    /**
     * Data provider for the valid password change test.
     *
     * @return array
     */
    public function providerValidPasswordChange()
    {
        return [
            ['admin', 'admin', 'thenewadminpassword'],
            ['admin', 'thenewadminpassword', '123xkaifua'],
            ['admin', '123xkaifua', 'admin']
        ];
    }

    /**
     * Returns the JWT for the given usename and password.
     *
     * @param string $username
     * @param string $password
     *
     * @return string
     */
    private function getAuthToken($username, $password)
    {
        return $this->route('/api/auth/login')
            ->parameters([
                'username' => $username,
                'password' => $password
            ])
            ->execute()
            ->getContent();
    }
}