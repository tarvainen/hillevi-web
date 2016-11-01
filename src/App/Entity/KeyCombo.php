<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * KeyCombo
 *
 * @ORM\Table(name="key_combo")
 * @ORM\Entity(repositoryClass="App\Repository\KeyComboRepository")
 */
class KeyCombo
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
     * @var string
     *
     * @ORM\Column(name="combo", type="string", length=255)
     */
    private $combo;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

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
     * @return KeyCombo
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
     * Set requestedAt
     *
     * @param \DateTime $requestedAt
     *
     * @return KeyCombo
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
     * Set combo
     *
     * @param string $combo
     *
     * @return KeyCombo
     */
    public function setCombo($combo)
    {
        $this->combo = $combo;

        return $this;
    }

    /**
     * Get combo
     *
     * @return string
     */
    public function getCombo()
    {
        return $this->combo;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return KeyCombo
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return KeyCombo
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
     * @return KeyCombo
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
}
