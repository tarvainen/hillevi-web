<?php

namespace App\Util;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Tests for DateUtil class.
 *
 * @package App\Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class DateUtilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the validator really returns a valid DateTime object.
     *
     * @param mixed $toValidate
     * @param mixed $fallback
     *
     * @dataProvider dataProviderDateTimes
     */
    public function testThatDateUtilValidateReturnsValidDateTimeObject($toValidate, $fallback)
    {
        $this->assertTrue(DateUtil::validate($toValidate, $fallback) instanceof \DateTime);
    }

    /**
     * Data provider for the testThatDateUtilValidateReturnsValidDateTimeObject test.
     *
     * @return array
     */
    public function dataProviderDateTimes()
    {
        return [
            ['this_is_not_a_date_time', null],
            [new \DateTime(), null],
            [null, null],
            [null, new \DateTime()]
        ];
    }
}