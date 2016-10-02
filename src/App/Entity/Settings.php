<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Settings
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass="App\Repository\SettingsRepository")
 */
class Settings extends EntityBase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="notifications", type="boolean")
     */
    private $notifications = true;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set notifications
     *
     * @param boolean $notifications
     *
     * @return Settings
     */
    public function setNotifications($notifications)
    {
        if (is_string($notifications)) {
            $this->notifications = $notifications === 'true' ? true : false;
        } else {
            $this->notifications = $notifications;
        }

        return $this;
    }

    /**
     * Get notifications
     *
     * @return bool
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
}
