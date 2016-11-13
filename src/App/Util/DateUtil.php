<?php

namespace App\Util;

/**
 * Utility class to contain some generic operations for date times.
 *
 * @package App\Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class DateUtil
{
    /** Constants for the date and datetime formatting. */
    const DATETIME_DB = 'Y-m-d H:i:s';
    const DATE_DB     = 'Y-m-d';

    /** Let's use these as default for 'no range' selects */
    const SMALL_ENOUGH = '1900-01-01';
    const BIG_ENOUGH   = '2036-01-01';

    /**
     * Validate the input datetime so this will return always a datetime object.
     *
     * @param mixed          $dateTime
     * @param \DateTime|null $fallback
     *
     * @return \DateTime
     */
    public static function validate($dateTime, \DateTime $fallback = null)
    {
        if (isset($dateTime)) {
            if ($dateTime instanceof \DateTime) {
                return $dateTime;
            }

            try {
                return new \DateTime($dateTime);
            } catch (\Exception $e) {
                // to fallback
            }
        }

        if (!is_null($fallback)) {
            return $fallback;
        }

        return new \DateTime();
    }
}