<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * ComputerUsageSnapshot
 *
 * @ORM\Table(name="computer_usage_snapshot")
 * @ORM\Entity(repositoryClass="App\Repository\ComputerUsageSnapshotRepository")
 */
class ComputerUsageSnapshot
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
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="datetime")
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endTime", type="datetime")
     */
    private $endTime;

    /**
     * @var string
     *
     * @ORM\Column(name="activeUsage", type="decimal", precision=10, scale=2)
     */
    private $activeUsage;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     */
    private $user;


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
     * Set user
     *
     * @param User $user
     *
     * @return ComputerUsageSnapshot
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return ComputerUsageSnapshot
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     *
     * @return ComputerUsageSnapshot
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set activeUsage
     *
     * @param string $activeUsage
     *
     * @return ComputerUsageSnapshot
     */
    public function setActiveUsage($activeUsage)
    {
        $this->activeUsage = $activeUsage;

        return $this;
    }

    /**
     * Get activeUsage
     *
     * @return string
     */
    public function getActiveUsage()
    {
        return $this->activeUsage;
    }
}

