<?php

namespace App\Annotation;

/**
 * The abstract base class for the annotations.
 *
 * @package App\Annotation
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
abstract class AbstractAnnotation
{
    /**
     * Validates some data due to the annotation content maybe? Implement this in the child class.
     *
     * @return mixed
     */
    abstract public function validate();

    /**
     * Parses the annotation value from the whole string.
     *
     * @param   string  $annotation
     *
     * @return  mixed
     */
    protected function parseSimpleAnnotation($annotation)
    {
        return explode('=', $annotation)[1];
    }
}
