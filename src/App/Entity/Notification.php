<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use JMS\Serializer\Annotation\Groups;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 */
class Notification
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"common"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=6000)
     *
     * @Groups({"common"})
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="integer")
     *
     * @Groups({"common"})
     */
    private $priority = 0;

    /**
     * @ManyToOne(targetEntity="User")
     *
     * @var User
     *
     * @Groups({"special"})
     */
    private $user;

    /**
     * @var bool
     *
     * @Groups({"common"})
     */
    private $dismissed = false;


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
     * Set content
     *
     * @param string $content
     *
     * @return Notification
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     *
     * @return Notification
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set the user
     *
     * @param  User $user
     *
     * @return Notification
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get users
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set notification either dismissed or just missed (ha-ha)
     *
     * @param   bool $dismissed
     *
     * @return  Notification
     */
    public function setDismissed($dismissed)
    {
        $this->dismissed = $dismissed;

        return $this;
    }

    /**
     * Get dismissed.
     *
     * @return bool
     */
    public function getDismissed()
    {
        return $this->dismissed;
    }
}
