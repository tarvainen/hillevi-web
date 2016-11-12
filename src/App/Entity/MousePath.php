<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * MousePath
 *
 * @ORM\Table(name="mouse_path")
 * @ORM\Entity(repositoryClass="App\Repository\MousePathRepository")
 */
class MousePath
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
     * @ORM\Column(name="path", type="string", length=9999999999)
     */
    private $path;

    /**
     * @var float
     *
     * @ORM\Column(name="totalDistance", type="decimal", precision=17, scale=2)
     */
    private $totalDistance;

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
     * @return MousePath
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
     * @return MousePath
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
     * @return MousePath
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
     * Set path
     *
     * @param string $path
     *
     * @return MousePath
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set total distance
     *
     * @param float $totalDistance
     *
     * @return MousePath
     */
    public function setTotalDistance($totalDistance)
    {
        $this->totalDistance = $totalDistance;

        return $this;
    }

    /**
     * Get total distance
     *
     * @return float
     */
    public function getTotalDistance()
    {
        return $this->totalDistance;
    }
}

