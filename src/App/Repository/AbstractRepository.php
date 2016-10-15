<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * The abstract repository to force all other repositories to implement certain methods.
 *
 * @package App\Repository
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
abstract class AbstractRepository extends EntityRepository
{
    abstract public function my();
}
