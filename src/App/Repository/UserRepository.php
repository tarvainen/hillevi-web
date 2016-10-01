<?php

namespace App\Repository;

/**
 * The repository class for the User entity.
 */
class UserRepository extends BaseRepository
{
    /**
     * Checks user rights for this module.
     *
     * @return UserRepository
     */
    public function scopeRights()
    {
        // TODO: Implement scopeRights() method.

        return $this;
    }
}
