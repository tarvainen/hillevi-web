<?php

namespace App\Annotation;

use App\Exception\UnauthorizedException;

/**
 * Permission annotation for checking user permissions before granting access on controllers etc.
 *
 * @Annotation
 *
 * @package App\Annotation
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class Permission extends ContainerAwareAnnotation
{
    /**
     * The perm annotation value.
     *
     * @var string
     */
    public $perm;

    /**
     * Does the validation for the annotation.
     *
     * @return void
     */
    public function validate()
    {
        $permissions = explode(',', $this->parseSimpleAnnotation($this->perm));

        // Check if admin, then there has to be no control over any routes
        if ($this->controller->getUserEntity()->isAdmin()) {
            return;
        }

        $rightEntities = $this->controller->getUserEntity()->getRights();

        /**
         * Map the entity names to an array.
         */
        $userRights = $rightEntities->map(function ($entity) {
            /**
             * @var \App\Entity\Permission $entity
             */
            return $entity->getName();
        })->toArray();

        // If some of the permissions is missing, we will prevent the route executing
        foreach ($permissions as $permission) {
            if (!in_array($permission, $userRights)) {
                throw new UnauthorizedException();
            }
        }
    }
}
