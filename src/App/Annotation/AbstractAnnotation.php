<?php

namespace App\Annotation;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     *
     * @return mixed
     */
    abstract public function validate(Request $request);

    /**
     * Parses the annotation value from the whole string.
     *
     * @param   string  $annotation
     *
     * @return  mixed
     */
    protected function parseSimpleAnnotation($annotation)
    {
        $parts = explode('=', $annotation);

        if (count($parts) > 1) {
            return $parts[1];
        }

        return $parts[0];
    }
}
