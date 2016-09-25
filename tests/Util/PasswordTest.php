<?php

namespace Util;

use App\Util\Password;

/**
 * Tests for the Password class.
 *
 * @package Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class PasswordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Function to test that two hashed passwords are not the same!
     *
     * @param string    $a
     * @param string    $b
     *
     * @dataProvider    providerNoSamePassword
     */
    public function testNoSamePassword($a, $b)
    {
        $this->assertNotEquals(Password::hash($a), Password::hash($b));
    }

    /**
     * Tests that the generated password hash matches the plain when tested.,
     *
     * @param string    $plain
     * @param string    $toHash
     *
     * @dataProvider    providerPasswordMatch
     */
    public function testPasswordMatch($plain, $toHash)
    {
        $this->assertTrue(Password::test($plain, Password::hash($toHash)));
    }

    /**
     * Tests that the wrong password does not match with the hash.
     *
     * @param string    $plain
     * @param string    $hash
     *
     * @dataProvider    providerPasswordMismatch
     */
    public function testPasswordMismatch($plain, $hash)
    {
        $this->assertFalse(Password::test($plain, $hash));
    }

    /**
     * Data provider for the no same password test.
     *
     * @return array
     */
    public function providerNoSamePassword()
    {
        return [
            ['test', 'test'],
            ['123test', '123test'],
            ['*6712%', '*6712%']
        ];
    }

    /**
     * Data provider for the password match test.
     *
     * @return array
     */
    public function providerPasswordMatch()
    {
        return [
            ['12**verystrong', '12**verystrong'],
            ['', ''],
            ['2313123123124125312312312124', '2313123123124125312312312124'],
            ['\\\\\\//', '\\\\\\//']
        ];
    }

    /**
     * Data provider for the password mismatch test.
     *
     * @return array
     */
    public function providerPasswordMismatch()
    {
        return [
            ['', 'asd'],
            ['1234', ''],
            ['1234', '12345'],
            ['1234', '1234 '],
            ['11', '111'],
            ['ABC', 'abc']
        ];
    }
}
