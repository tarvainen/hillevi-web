<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * MousePosition
 *
 * @ORM\Table(name="mouse_position")
 * @ORM\Entity(repositoryClass="App\Repository\MousePositionRepository")
 */
class MousePosition
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
     * @var int
     *
     * @ORM\Column(name="coordinateX", type="integer")
     */
    private $coordinateX;

    /**
     * @var int
     *
     * @ORM\Column(name="coordinateY", type="integer")
     */
    private $coordinateY;

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
     * @var \DateTime
     *
     * @ORM\Column(name="requestedAt", type="datetime")
     */
    private $requestedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="screenWidth", type="integer")
     */
    private $screenWidth;

    /**
     * @var int
     *
     * @ORM\Column(name="screenHeight", type="integer")
     */
    private $screenHeight;

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
     * @return MousePosition
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
     * Set coordinateX
     *
     * @param integer $coordinateX
     *
     * @return MousePosition
     */
    public function setCoordinateX($coordinateX)
    {
        $this->coordinateX = $coordinateX;

        return $this;
    }

    /**
     * Get coordinateX
     *
     * @return int
     */
    public function getCoordinateX()
    {
        return $this->coordinateX;
    }

    /**
     * Set coordinateY
     *
     * @param integer $coordinateY
     *
     * @return MousePosition
     */
    public function setCoordinateY($coordinateY)
    {
        $this->coordinateY = $coordinateY;

        return $this;
    }

    /**
     * Get coordinateY
     *
     * @return int
     */
    public function getCoordinateY()
    {
        return $this->coordinateY;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return MousePosition
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
     * @return MousePosition
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
     * Set requestedAt
     *
     * @param \DateTime $requestedAt
     *
     * @return MousePosition
     */
    public function setRequestedAt($requestedAt)
    {
        $this->requestedAt = $requestedAt;

        return $this;
    }

    /**
     * Get requestedAt
     *
     * @return \DateTime
     */
    public function getRequestedAt()
    {
        return $this->requestedAt;
    }

    /**
     * Set screenWidth
     *
     * @param integer $screenWidth
     *
     * @return MousePosition
     */
    public function setScreenWidth($screenWidth)
    {
        $this->screenWidth = $screenWidth;

        return $this;
    }

    /**
     * Get screenWidth
     *
     * @return int
     */
    public function getScreenWidth()
    {
        return $this->screenWidth;
    }

    /**
     * Set screenHeight
     *
     * @param integer $screenHeight
     *
     * @return MousePosition
     */
    public function setScreenHeight($screenHeight)
    {
        $this->screenHeight = $screenHeight;

        return $this;
    }

    /**
     * Get screenHeight
     *
     * @return int
     */
    public function getScreenHeight()
    {
        return $this->screenHeight;
    }
}
