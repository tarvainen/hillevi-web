<?php

namespace Util;
use App\Util\Arrays;
use App\Util\Formatter;

/**
 * Tests for the Formatter class.
 *
 * @package Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class FormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the toPadded function returns right values.
     *
     * @dataProvider providerUnpaddedToPaddedValues
     */
    public function testThatPaddedReallyAddsPaddingValues($value, $padd, $expected)
    {
        $this->assertEquals(Formatter::toPadded($value, $padd), $expected);
    }

    /**
     * Data provider for the testThatPaddedReallyAddsPaddingValues test.
     * @return array
     */
    public function providerUnpaddedToPaddedValues()
    {
        return [
            [2, 5, '00002'],
            [1, null, '01'],
            ['01', null, '01'],
            [10, -2, '10'],
            ['012', 3, '012'],
            ['', 2, '00']
        ];
    }
}
