<?php

namespace App\Reader;

use App\Util\ContentType;

/**
 * A xml interface reader class.
 *
 * @package App\Reader
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class XmlInterfaceReader extends BaseInterfaceReader
{
    /**
     * XmlInterfaceReader constructor.
     */
    public function __construct()
    {
        $this->type = ContentType::TYPE_XML;
    }

    /**
     * Parses the xml data to the destination array.
     *
     * @param   string $data
     *
     * @return  array
     */
    public function handleData($data)
    {
        return array();
    }
}
