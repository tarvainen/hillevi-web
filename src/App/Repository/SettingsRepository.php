<?php

namespace App\Repository;

/**
 * SettingsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SettingsRepository extends BaseRepository
{
    /**
     * Checks user rights for this module.
     *
     * @return SettingsRepository
     */
    public function scopeRights()
    {
        // TODO: Implement scopeRights() method.

        return $this;
    }
}
