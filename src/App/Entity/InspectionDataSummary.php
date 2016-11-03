<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * InspectionDataSummary
 *
 * @ORM\Table(name="inspection_data_summary")
 * @ORM\Entity(repositoryClass="App\Repository\InspectionDataSummaryRepository")
 */
class InspectionDataSummary
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
     * @var int
     *
     * @ORM\Column(name="keysTyped", type="integer")
     */
    private $keysTyped;

    /**
     * @var string
     *
     * @ORM\Column(name="typingSpeed", type="decimal", precision=10, scale=2)
     */
    private $typingSpeed;

    /**
     * @var int
     *
     * @ORM\Column(name="keyCombos", type="integer")
     */
    private $keyCombos;

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
     * @return InspectionDataSummary
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
     * @return InspectionDataSummary
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
     * @return InspectionDataSummary
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
     * Set keysTyped
     *
     * @param integer $keysTyped
     *
     * @return InspectionDataSummary
     */
    public function setKeysTyped($keysTyped)
    {
        $this->keysTyped = $keysTyped;

        return $this;
    }

    /**
     * Get keysTyped
     *
     * @return int
     */
    public function getKeysTyped()
    {
        return $this->keysTyped;
    }

    /**
     * Set typingSpeed
     *
     * @param string $typingSpeed
     *
     * @return InspectionDataSummary
     */
    public function setTypingSpeed($typingSpeed)
    {
        $this->typingSpeed = $typingSpeed;

        return $this;
    }

    /**
     * Get typingSpeed
     *
     * @return string
     */
    public function getTypingSpeed()
    {
        return $this->typingSpeed;
    }

    /**
     * Set keyCombos
     *
     * @param integer $keyCombos
     *
     * @return InspectionDataSummary
     */
    public function setKeyCombos($keyCombos)
    {
        $this->keyCombos = $keyCombos;

        return $this;
    }

    /**
     * Get keyCombos
     *
     * @return int
     */
    public function getKeyCombos()
    {
        return $this->keyCombos;
    }
}
