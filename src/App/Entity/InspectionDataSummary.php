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
    const TABLE_NAME                  = 'inspection_data_summary';
    const FIELD_TYPING_SPEED          = 'typingSpeed';
    const FIELD_ACTIVITY_PERCENTAGE   = 'activityPercentage';
    const FIELD_PASTE_PERCENTAGE      = 'pastePercentage';
    const FIELD_MOUSE_TRAVEL_DISTANCE = 'mouseTravelDistance';

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
     * @var float
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
     * @var int
     *
     * @ORM\Column(name="pasted", type="integer")
     */
    private $pasted;

    /**
     * @var float
     *
     * @ORM\Column(name="activityPercentage", type="decimal", precision=10, scale=2)
     */
    private $activityPercentage;

    /**
     * @var float
     *
     * @ORM\Column(name="pastePercentage", type="decimal", precision=10, scale=2)
     */
    private $pastePercentage;

    /**
     * @var int
     *
     * @ORM\Column(name="mouseTravelDistance", type="integer")
     */
    private $mouseTravelDistance;

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
     * @param float $typingSpeed
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
     * @return float
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

    /**
     * Set pasted
     *
     * @param integer $pasted
     *
     * @return InspectionDataSummary
     */
    public function setPasted($pasted)
    {
        $this->pasted = $pasted;

        return $this;
    }

    /**
     * Get pasted
     *
     * @return int
     */
    public function getPasted()
    {
        return $this->pasted;
    }

    /**
     * Set activity percentage
     *
     * @param float $activityPercentage
     *
     * @return InspectionDataSummary
     */
    public function setActivityPercentage($activityPercentage)
    {
        $this->activityPercentage = $activityPercentage;

        return $this;
    }

    /**
     * Get activity percentage.
     *
     * @return float
     */
    public function getActivityPercentage()
    {
        return $this->activityPercentage;
    }

    /**
     * Set paste percentage
     *
     * @param float $pastePercentage
     *
     * @return InspectionDataSummary
     */
    public function setPastePercentage($pastePercentage)
    {
        $this->pastePercentage = $pastePercentage;

        return $this;
    }

    /**
     * Get paste percentage.
     *
     * @return float
     */
    public function getPastePercentage()
    {
        return $this->pastePercentage;
    }

    /**
     * Set mouse travel distance
     *
     * @param int $mouseTravelDistance
     *
     * @return InspectionDataSummary
     */
    public function setMouseTravelDistance($mouseTravelDistance)
    {
        $this->mouseTravelDistance = $mouseTravelDistance;

        return $this;
    }

    /**
     * Get mouse travel distance (in pixels).
     *
     * @return int
     */
    public function getMouseTravelDistance()
    {
        return $this->mouseTravelDistance;
    }
}
