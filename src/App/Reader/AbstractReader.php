<?php

namespace App\Reader;

/**
 * Abstract reader base class.
 *
 * @package App\Reader
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
abstract class AbstractReader
{
    /**
     * The type of the reader.
     *
     * @var string
     */
    protected $type;

    /**
     * Container for the interface's columns.
     *
     * @var array
     */
    protected $columns;

    /**
     * The url of the api.
     *
     * @var string
     */
    protected $url;

    /**
     * AbstractReader constructor.
     */
    abstract public function __construct();

    /**
     * Executes the reader. Reads the data from the api, validates the returned data and saves it to the database.
     *
     * @return void
     */
    public function execute()
    {
        if ($this->isValid()) {
            $this->saveData(
                $this->handleData(
                    $this->getData()
                )
            );
        }
    }

    /**
     * Function to fetch data from the api.
     *
     * @return mixed
     */
    abstract protected function getData();

    /**
     * Function to handle the data from the api.
     *
     * @param   string $data
     *
     * @return mixed
     */
    abstract protected function handleData($data);

    /**
     * Function to save the data to the database.
     *
     * @param   array $data
     *
     * @return  mixed
     */
    abstract protected function saveData($data);

    /**
     * Check if this instance of the reader is valid. We will execute only this
     * method returns true. Otherwise we can't trust that this is a real deal.
     *
     * @return mixed
     */
    abstract protected function isValid();

    /**
     * Creates the notification to be sent for the user.
     *
     * @return mixed
     */
    abstract protected function notify();
}
