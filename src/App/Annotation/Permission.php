<?php

namespace App\Annotation;

use App\Entity\User;
use App\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManager;
use Namshi\JOSE\SimpleJWS;
use Symfony\Component\HttpFoundation\Request;

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
     * @var User|null;
     */
    private $user;

    /**
     * Does the validation for the annotation.
     *
     * @return void
     */
    public function validate()
    {
        if (!$this->validateJWT()) {
            throw new UnauthorizedException();
        }

        $permissions = explode(',', $this->parseSimpleAnnotation($this->perm));

        // Check if admin, then there has to be no control over any routes
        if ($this->user->isAdmin()) {
            return;
        }

        $rightEntities = $this->user->getRights();

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

    /**
     * Validates the JWT on every route we have the @Permission set.
     * @return bool
     */
    private function validateJWT()
    {
        $request = Request::createFromGlobals();

        $jwt = $request->headers->get('authorization');

        try {
            $jws = SimpleJWS::load($jwt);
        } catch (\InvalidArgumentException $e) {
            return false;
        }

        $doctrine = $this->container->get('doctrine');

        /**
         * @var EntityManager $em
         */
        $em = $doctrine->getManager();

        $user = $em->find('App:User', $jws->getPayload()['uid']);

        if (!$user) {
            return false;
        }

        $this->user = $user;

        return $this->user->getToken() === $jwt;
    }
}
