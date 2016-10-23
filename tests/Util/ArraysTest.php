<?php

namespace Util;
use App\Util\Arrays;

/**
 * Tests for the Arrays class.
 *
 * @package Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class ArraysTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the multidimensional array check returns true on multidimensional array.
     *
     * @dataProvider providerMultidimensionalArrays
     */
    public function testIsMultidimensionalArray($a)
    {
        $this->assertTrue(Arrays::isMultidimensionalArray($a));
    }

    /**
     * Test that the multidimensional array check returns false on one dimensional array.
     *
     * @dataProvider providerOneDimensionalArrays
     */
    public function testIsNotMultidimensionalArray($a)
    {
        $this->assertFalse(Arrays::isMultidimensionalArray($a));
    }

    /**
     * Data provider for the password mismatch test.
     *
     * @return array
     */
    public function providerMultidimensionalArrays()
    {
        return [
            [
                [
                    'this is not an array',
                    [
                        'array' => true,
                    ]
                ]
            ],
            [
                [
                    'random stuff',
                    'doggie' => ['cattie' => 2]
                ]
            ],
            [
                [
                    [
                        'id' => 0,
                        'name' => 'Jari',
                    ],
                    [
                        'id' => 1,
                        'name' => 'Kari'
                    ]
                ]
            ]
        ];
    }

    /**
     * Provide some one dimensional arrays.
     *
     * @return array
     */
    public function providerOneDimensionalArrays()
    {
        return [
            ['random stuff' => true],
            ['random > 5' => false],
            [0],
        ];
    }
}
