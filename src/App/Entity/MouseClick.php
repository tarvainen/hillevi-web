<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * MouseClick
 *
 * @ORM\Table(name="mouse_click")
 * @ORM\Entity(repositoryClass="App\Repository\MouseClickRepository")
 */
class MouseClick
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
     * @ORM\Column(name="button", type="integer")
     */
    private $button;

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
     * @return MouseClick
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
     * Set button
     *
     * @param integer $button
     *
     * @return MouseClick
     */
    public function setButton($button)
    {
        $this->button = $button;

        return $this;
    }

    /**
     * Get button
     *
     * @return int
     */
    public function getButton()
    {
        return $this->button;
    }

    /**
     * Set coordinateX
     *
     * @param integer $coordinateX
     *
     * @return MouseClick
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
     * @return MouseClick
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
     * @return MouseClick
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
     * @return MouseClick
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
     * @return MouseClick
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
}
