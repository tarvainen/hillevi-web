<?php

namespace App\Reader;

use App\Util\Curl;
use App\Util\FieldFormatter;
use App\Util\Logger;

/**
 * Base interface reader class for all other specific type readers.
 *
 * @package App\Reader
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
abstract class BaseInterfaceReader extends AbstractReader
{
    /**
     * Fetch data from the url using curl.
     *
     * @return mixed
     */
    public function getData()
    {
        try {
            return Curl::read($this->url);
        } catch (\Exception $e) {
            $this->notify();
        }

        return [];
    }

    /**
     * Maps the desired columns from the api's data.
     *
     * @param array $data
     *
     * @return array
     */
    public function mapData($data)
    {
        $columns = $this->columns;

        // Filter out the unnecessary values
        $filter = function ($value, $key) use ($columns) {
            return !is_null($value) && array_key_exists($key, $columns);
        };

        array_filter($data, $filter, ARRAY_FILTER_USE_BOTH);

        return $data;
    }

    /**
     * Sends notify to the user if the api reading fails.
     *
     * @return void
     */
    public function notify()
    {
        Logger::log('FAILED TO GET DATA FROM THE API.');
    }

    /**
     * Checks if the reader values are valid.
     *
     * @return bool
     */
    public function isValid()
    {
        // TODO: improve this check A LOT!
        return !empty($this->url) && !empty($this->columns);
    }
}
