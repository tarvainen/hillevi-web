<?php

namespace App\Entity;

use App\Util\Logger;

/**
 * The base class for all entities.
 *
 * @package App\Entity
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class EntityBase
{
    /**
     * Loads the data to the entity from an array in the most dynamic way. We just have to make sure
     * that there is always a following combination available in the entity. This is handy
     * for like update operations.
     *
     * private $parameter;
     * public function setParameter($parameter);
     *
     * This will not whatsoever handle any associations so make sure you handle them yourself.
     *
     * @param array $data
     */
    public function fromArray(array $data)
    {
        foreach ($data as $key => $value) {
            Logger::log(get_class($this));
            if (property_exists(get_class($this), $key) && method_exists(get_class($this), 'set' . strtoupper($key))) {
                $this->{'set' . strtoupper($key)}($value);
            }
        }
    }
}
