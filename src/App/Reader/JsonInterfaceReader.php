<?php

namespace App\Reader;

use App\Util\ContentType;
use App\Util\Json;

class JsonInterfaceReader extends BaseInterfaceReader
{
    /**
     * JsonInterfaceReader constructor.
     */
    public function __construct()
    {
        $this->type = ContentType::TYPE_JSON;
    }

    public function handleData($data)
    {
        return Json::decode($data);
    }
}
