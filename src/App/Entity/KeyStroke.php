<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use JMS\Serializer\Annotation\Type;

/**
 * KeyStroke
 *
 * @ORM\Table(name="keystroke")
 * @ORM\Entity(repositoryClass="App\Repository\KeyStrokeRepository")
 */
class KeyStroke
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
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F12", type="integer")
     */
    private $key_F12 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F1", type="integer")
     */
    private $key_F1 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F2", type="integer")
     */
    private $key_F2 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F3", type="integer")
     */
    private $key_F3 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F4", type="integer")
     */
    private $key_F4 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F5", type="integer")
     */
    private $key_F5 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F6", type="integer")
     */
    private $key_F6 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F7", type="integer")
     */
    private $key_F7 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F8", type="integer")
     */
    private $key_F8 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F9", type="integer")
     */
    private $key_F9 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F10", type="integer")
     */
    private $key_F10 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F11", type="integer")
     */
    private $key_F11 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_PrintScreen", type="integer")
     */
    private $key_PrintScreen = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_ScrollLock", type="integer")
     */
    private $key_ScrollLock = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Pause", type="integer")
     */
    private $key_Pause = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_1", type="integer")
     */
    private $key_1 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_2", type="integer")
     */
    private $key_2 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_3", type="integer")
     */
    private $key_3 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_4", type="integer")
     */
    private $key_4 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_5", type="integer")
     */
    private $key_5 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_6", type="integer")
     */
    private $key_6 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_7", type="integer")
     */
    private $key_7 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_8", type="integer")
     */
    private $key_8 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_9", type="integer")
     */
    private $key_9 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_10", type="integer")
     */
    private $key_10 = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Plus", type="integer")
     */
    private $key_Plus = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Backspace", type="integer")
     */
    private $key_Backspace = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Tab", type="integer")
     */
    private $key_Tab = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_CapsLock", type="integer")
     */
    private $key_CapsLock = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_LeftShift", type="integer")
     */
    private $key_LeftShift = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_LeftCtrl", type="integer")
     */
    private $key_LeftCtrl = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_LeftAlt", type="integer")
     */
    private $key_LeftAlt = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Space", type="integer")
     */
    private $key_Space = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Q", type="integer")
     */
    private $key_Q = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_W", type="integer")
     */
    private $key_W = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_E", type="integer")
     */
    private $key_E = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_R", type="integer")
     */
    private $key_R = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_T", type="integer")
     */
    private $key_T = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Y", type="integer")
     */
    private $key_Y = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_U", type="integer")
     */
    private $key_U = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_I", type="integer")
     */
    private $key_I = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_O", type="integer")
     */
    private $key_O = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_P", type="integer")
     */
    private $key_P = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Tilde", type="integer")
     */
    private $key_Tilde = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_A", type="integer")
     */
    private $key_A = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_S", type="integer")
     */
    private $key_S = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_D", type="integer")
     */
    private $key_D = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_F", type="integer")
     */
    private $key_F = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_G", type="integer")
     */
    private $key_G = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_H", type="integer")
     */
    private $key_H = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_J", type="integer")
     */
    private $key_J = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_K", type="integer")
     */
    private $key_K = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_L", type="integer")
     */
    private $key_L = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_OE", type="integer")
     */
    private $key_OE = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_AE", type="integer")
     */
    private $key_AE = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Multiply", type="integer")
     */
    private $key_Multiply = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Z", type="integer")
     */
    private $key_Z = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_X", type="integer")
     */
    private $key_X = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_C", type="integer")
     */
    private $key_C = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_V", type="integer")
     */
    private $key_V = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_B", type="integer")
     */
    private $key_B = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_N", type="integer")
     */
    private $key_N = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_M", type="integer")
     */
    private $key_M = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Comma", type="integer")
     */
    private $key_Comma = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Period", type="integer")
     */
    private $key_Period = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Minus", type="integer")
     */
    private $key_Minus = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_RightCtrl", type="integer")
     */
    private $key_RightCtrl = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Enter", type="integer")
     */
    private $key_Enter = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_RightShift", type="integer")
     */
    private $key_RightShift = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Insert", type="integer")
     */
    private $key_Insert = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Home", type="integer")
     */
    private $key_Home = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_PageUp", type="integer")
     */
    private $key_PageUp = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_PageDown", type="integer")
     */
    private $key_PageDown = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Delete", type="integer")
     */
    private $key_Delete = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_End", type="integer")
     */
    private $key_End = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Left", type="integer")
     */
    private $key_Left = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Up", type="integer")
     */
    private $key_Up = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Right", type="integer")
     */
    private $key_Right = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_Down", type="integer")
     */
    private $key_Down = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="key_NumLock", type="integer")
     */
    private $key_NumLock = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total = 0;

    /**
     * @var int
     *
     * @Type("integer")
     *
     * @ORM\Column(name="pasted", type="integer")
     */
    private $pasted = 0;

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
     * @var float
     *
     * @Type("float")
     *
     * @ORM\Column(name="keyDownTime", type="decimal", precision=10, scale=2)
     */
    private $keyDownTime = 0.0;


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
     * Set requestedAt
     *
     * @param \DateTime $requestedAt
     *
     * @return KeyStroke
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
     * Set user
     *
     * @param User $user
     *
     * @return KeyStroke
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
     * @return KeyStroke
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
     * @return KeyStroke
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
     * Set key_F12
     *
     * @param integer $key_F12
     *
     * @return KeyStroke
     */
    public function setKeyF12($key_F12)
    {
        $this->key_F12 = $key_F12;

        return $this;
    }

    /**
     * Get key_F12
     *
     * @return int
     */
    public function getKeyF12()
    {
        return $this->key_F12;
    }

    /**
     * Set key_F1
     *
     * @param integer $key_F1
     *
     * @return KeyStroke
     */
    public function setKeyF1($key_F1)
    {
        $this->key_F1 = $key_F1;

        return $this;
    }

    /**
     * Get key_F1
     *
     * @return int
     */
    public function getKeyF1()
    {
        return $this->key_F1;
    }

    /**
     * Set key_F2
     *
     * @param integer $key_F2
     *
     * @return KeyStroke
     */
    public function setKeyF2($key_F2)
    {
        $this->key_F2 = $key_F2;

        return $this;
    }

    /**
     * Get key_F2
     *
     * @return int
     */
    public function getKeyF2()
    {
        return $this->key_F2;
    }

    /**
     * Set key_F3
     *
     * @param integer $key_F3
     *
     * @return KeyStroke
     */
    public function setKeyF3($key_F3)
    {
        $this->key_F3 = $key_F3;

        return $this;
    }

    /**
     * Get key_F3
     *
     * @return int
     */
    public function getKeyF3()
    {
        return $this->key_F3;
    }

    /**
     * Set key_F4
     *
     * @param integer $key_F4
     *
     * @return KeyStroke
     */
    public function setKeyF4($key_F4)
    {
        $this->key_F4 = $key_F4;

        return $this;
    }

    /**
     * Get key_F4
     *
     * @return int
     */
    public function getKeyF4()
    {
        return $this->key_F4;
    }

    /**
     * Set key_F5
     *
     * @param integer $key_F5
     *
     * @return KeyStroke
     */
    public function setKeyF5($key_F5)
    {
        $this->key_F5 = $key_F5;

        return $this;
    }

    /**
     * Get key_F5
     *
     * @return int
     */
    public function getKeyF5()
    {
        return $this->key_F5;
    }

    /**
     * Set key_F6
     *
     * @param integer $key_F6
     *
     * @return KeyStroke
     */
    public function setKeyF6($key_F6)
    {
        $this->key_F6 = $key_F6;

        return $this;
    }

    /**
     * Get key_F6
     *
     * @return int
     */
    public function getKeyF6()
    {
        return $this->key_F6;
    }

    /**
     * Set key_F7
     *
     * @param integer $key_F7
     *
     * @return KeyStroke
     */
    public function setKeyF7($key_F7)
    {
        $this->key_F7 = $key_F7;

        return $this;
    }

    /**
     * Get key_F7
     *
     * @return int
     */
    public function getKeyF7()
    {
        return $this->key_F7;
    }

    /**
     * Set key_F8
     *
     * @param integer $key_F8
     *
     * @return KeyStroke
     */
    public function setKeyF8($key_F8)
    {
        $this->key_F8 = $key_F8;

        return $this;
    }

    /**
     * Get key_F8
     *
     * @return int
     */
    public function getKeyF8()
    {
        return $this->key_F8;
    }

    /**
     * Set key_F9
     *
     * @param integer $key_F9
     *
     * @return KeyStroke
     */
    public function setKeyF9($key_F9)
    {
        $this->key_F9 = $key_F9;

        return $this;
    }

    /**
     * Get key_F9
     *
     * @return int
     */
    public function getKeyF9()
    {
        return $this->key_F9;
    }

    /**
     * Set key_F10
     *
     * @param integer $key_F10
     *
     * @return KeyStroke
     */
    public function setKeyF10($key_F10)
    {
        $this->key_F10 = $key_F10;

        return $this;
    }

    /**
     * Get key_F10
     *
     * @return int
     */
    public function getKeyF10()
    {
        return $this->key_F10;
    }

    /**
     * Set key_F11
     *
     * @param integer $key_F11
     *
     * @return KeyStroke
     */
    public function setKeyF11($key_F11)
    {
        $this->key_F11 = $key_F11;

        return $this;
    }

    /**
     * Get key_F11
     *
     * @return int
     */
    public function getKeyF11()
    {
        return $this->key_F11;
    }

    /**
     * Set key_PrintScreen
     *
     * @param integer $key_PrintScreen
     *
     * @return KeyStroke
     */
    public function setKeyPrintScreen($key_PrintScreen)
    {
        $this->key_PrintScreen = $key_PrintScreen;

        return $this;
    }

    /**
     * Get key_PrintScreen
     *
     * @return int
     */
    public function getKeyPrintScreen()
    {
        return $this->key_PrintScreen;
    }

    /**
     * Set key_ScrollLock
     *
     * @param integer $key_ScrollLock
     *
     * @return KeyStroke
     */
    public function setKeyScrollLock($key_ScrollLock)
    {
        $this->key_ScrollLock = $key_ScrollLock;

        return $this;
    }

    /**
     * Get key_ScrollLock
     *
     * @return int
     */
    public function getKeyScrollLock()
    {
        return $this->key_ScrollLock;
    }

    /**
     * Set key_Pause
     *
     * @param integer $key_Pause
     *
     * @return KeyStroke
     */
    public function setKeyPause($key_Pause)
    {
        $this->key_Pause = $key_Pause;

        return $this;
    }

    /**
     * Get key_Pause
     *
     * @return int
     */
    public function getKeyPause()
    {
        return $this->key_Pause;
    }

    /**
     * Set key_1
     *
     * @param integer $key_1
     *
     * @return KeyStroke
     */
    public function setKey1($key_1)
    {
        $this->key_1 = $key_1;

        return $this;
    }

    /**
     * Get key_1
     *
     * @return int
     */
    public function getKey1()
    {
        return $this->key_1;
    }

    /**
     * Set key_2
     *
     * @param integer $key_2
     *
     * @return KeyStroke
     */
    public function setKey2($key_2)
    {
        $this->key_2 = $key_2;

        return $this;
    }

    /**
     * Get key_2
     *
     * @return int
     */
    public function getKey2()
    {
        return $this->key_2;
    }

    /**
     * Set key_3
     *
     * @param integer $key_3
     *
     * @return KeyStroke
     */
    public function setKey3($key_3)
    {
        $this->key_3 = $key_3;

        return $this;
    }

    /**
     * Get key_3
     *
     * @return int
     */
    public function getKey3()
    {
        return $this->key_3;
    }

    /**
     * Set key_4
     *
     * @param integer $key_4
     *
     * @return KeyStroke
     */
    public function setKey4($key_4)
    {
        $this->key_4 = $key_4;

        return $this;
    }

    /**
     * Get key_4
     *
     * @return int
     */
    public function getKey4()
    {
        return $this->key_4;
    }

    /**
     * Set key_5
     *
     * @param integer $key_5
     *
     * @return KeyStroke
     */
    public function setKey5($key_5)
    {
        $this->key_5 = $key_5;

        return $this;
    }

    /**
     * Get key_5
     *
     * @return int
     */
    public function getKey5()
    {
        return $this->key_5;
    }

    /**
     * Set key_6
     *
     * @param integer $key_6
     *
     * @return KeyStroke
     */
    public function setKey6($key_6)
    {
        $this->key_6 = $key_6;

        return $this;
    }

    /**
     * Get key_6
     *
     * @return int
     */
    public function getKey6()
    {
        return $this->key_6;
    }

    /**
     * Set key_7
     *
     * @param integer $key_7
     *
     * @return KeyStroke
     */
    public function setKey7($key_7)
    {
        $this->key_7 = $key_7;

        return $this;
    }

    /**
     * Get key_7
     *
     * @return int
     */
    public function getKey7()
    {
        return $this->key_7;
    }

    /**
     * Set key_8
     *
     * @param integer $key_8
     *
     * @return KeyStroke
     */
    public function setKey8($key_8)
    {
        $this->key_8 = $key_8;

        return $this;
    }

    /**
     * Get key_8
     *
     * @return int
     */
    public function getKey8()
    {
        return $this->key_8;
    }

    /**
     * Set key_9
     *
     * @param integer $key_9
     *
     * @return KeyStroke
     */
    public function setKey9($key_9)
    {
        $this->key_9 = $key_9;

        return $this;
    }

    /**
     * Get key_9
     *
     * @return int
     */
    public function getKey9()
    {
        return $this->key_9;
    }

    /**
     * Set key_10
     *
     * @param integer $key_10
     *
     * @return KeyStroke
     */
    public function setKey10($key_10)
    {
        $this->key_10 = $key_10;

        return $this;
    }

    /**
     * Get key_10
     *
     * @return int
     */
    public function getKey10()
    {
        return $this->key_10;
    }

    /**
     * Set key_Plus
     *
     * @param integer $key_Plus
     *
     * @return KeyStroke
     */
    public function setKeyPlus($key_Plus)
    {
        $this->key_Plus = $key_Plus;

        return $this;
    }

    /**
     * Get key_Plus
     *
     * @return int
     */
    public function getKeyPlus()
    {
        return $this->key_Plus;
    }

    /**
     * Set key_Backspace
     *
     * @param integer $key_Backspace
     *
     * @return KeyStroke
     */
    public function setKeyBackspace($key_Backspace)
    {
        $this->key_Backspace = $key_Backspace;

        return $this;
    }

    /**
     * Get key_Backspace
     *
     * @return int
     */
    public function getKeyBackspace()
    {
        return $this->key_Backspace;
    }

    /**
     * Set key_Tab
     *
     * @param integer $key_Tab
     *
     * @return KeyStroke
     */
    public function setKeyTab($key_Tab)
    {
        $this->key_Tab = $key_Tab;

        return $this;
    }

    /**
     * Get key_Tab
     *
     * @return int
     */
    public function getKeyTab()
    {
        return $this->key_Tab;
    }

    /**
     * Set key_CapsLock
     *
     * @param integer $key_CapsLock
     *
     * @return KeyStroke
     */
    public function setKeyCapsLock($key_CapsLock)
    {
        $this->key_CapsLock = $key_CapsLock;

        return $this;
    }

    /**
     * Get key_CapsLock
     *
     * @return int
     */
    public function getKeyCapsLock()
    {
        return $this->key_CapsLock;
    }

    /**
     * Set key_LeftShift
     *
     * @param integer $key_LeftShift
     *
     * @return KeyStroke
     */
    public function setKeyLeftShift($key_LeftShift)
    {
        $this->key_LeftShift = $key_LeftShift;

        return $this;
    }

    /**
     * Get key_LeftShift
     *
     * @return int
     */
    public function getKeyLeftShift()
    {
        return $this->key_LeftShift;
    }

    /**
     * Set key_LeftCtrl
     *
     * @param integer $key_LeftCtrl
     *
     * @return KeyStroke
     */
    public function setKeyLeftCtrl($key_LeftCtrl)
    {
        $this->key_LeftCtrl = $key_LeftCtrl;

        return $this;
    }

    /**
     * Get key_LeftCtrl
     *
     * @return int
     */
    public function getKeyLeftCtrl()
    {
        return $this->key_LeftCtrl;
    }

    /**
     * Set key_LeftAlt
     *
     * @param integer $key_LeftAlt
     *
     * @return KeyStroke
     */
    public function setKeyLeftAlt($key_LeftAlt)
    {
        $this->key_LeftAlt = $key_LeftAlt;

        return $this;
    }

    /**
     * Get key_LeftAlt
     *
     * @return int
     */
    public function getKeyLeftAlt()
    {
        return $this->key_LeftAlt;
    }

    /**
     * Set key_Space
     *
     * @param integer $key_Space
     *
     * @return KeyStroke
     */
    public function setKeySpace($key_Space)
    {
        $this->key_Space = $key_Space;

        return $this;
    }

    /**
     * Get key_Space
     *
     * @return int
     */
    public function getKeySpace()
    {
        return $this->key_Space;
    }

    /**
     * Set key_Q
     *
     * @param integer $key_Q
     *
     * @return KeyStroke
     */
    public function setKeyQ($key_Q)
    {
        $this->key_Q = $key_Q;

        return $this;
    }

    /**
     * Get key_Q
     *
     * @return int
     */
    public function getKeyQ()
    {
        return $this->key_Q;
    }

    /**
     * Set key_W
     *
     * @param integer $key_W
     *
     * @return KeyStroke
     */
    public function setKeyW($key_W)
    {
        $this->key_W = $key_W;

        return $this;
    }

    /**
     * Get key_W
     *
     * @return int
     */
    public function getKeyW()
    {
        return $this->key_W;
    }

    /**
     * Set key_E
     *
     * @param integer $key_E
     *
     * @return KeyStroke
     */
    public function setKeyE($key_E)
    {
        $this->key_E = $key_E;

        return $this;
    }

    /**
     * Get key_E
     *
     * @return int
     */
    public function getKeyE()
    {
        return $this->key_E;
    }

    /**
     * Set key_R
     *
     * @param integer $key_R
     *
     * @return KeyStroke
     */
    public function setKeyR($key_R)
    {
        $this->key_R = $key_R;

        return $this;
    }

    /**
     * Get key_R
     *
     * @return int
     */
    public function getKeyR()
    {
        return $this->key_R;
    }

    /**
     * Set key_T
     *
     * @param integer $key_T
     *
     * @return KeyStroke
     */
    public function setKeyT($key_T)
    {
        $this->key_T = $key_T;

        return $this;
    }

    /**
     * Get key_T
     *
     * @return int
     */
    public function getKeyT()
    {
        return $this->key_T;
    }

    /**
     * Set key_Y
     *
     * @param integer $key_Y
     *
     * @return KeyStroke
     */
    public function setKeyY($key_Y)
    {
        $this->key_Y = $key_Y;

        return $this;
    }

    /**
     * Get key_Y
     *
     * @return int
     */
    public function getKeyY()
    {
        return $this->key_Y;
    }

    /**
     * Set key_U
     *
     * @param integer $key_U
     *
     * @return KeyStroke
     */
    public function setKeyU($key_U)
    {
        $this->key_U = $key_U;

        return $this;
    }

    /**
     * Get key_U
     *
     * @return int
     */
    public function getKeyU()
    {
        return $this->key_U;
    }

    /**
     * Set key_I
     *
     * @param integer $key_I
     *
     * @return KeyStroke
     */
    public function setKeyI($key_I)
    {
        $this->key_I = $key_I;

        return $this;
    }

    /**
     * Get key_I
     *
     * @return int
     */
    public function getKeyI()
    {
        return $this->key_I;
    }

    /**
     * Set key_O
     *
     * @param integer $key_O
     *
     * @return KeyStroke
     */
    public function setKeyO($key_O)
    {
        $this->key_O = $key_O;

        return $this;
    }

    /**
     * Get key_O
     *
     * @return int
     */
    public function getKeyO()
    {
        return $this->key_O;
    }

    /**
     * Set key_P
     *
     * @param integer $key_P
     *
     * @return KeyStroke
     */
    public function setKeyP($key_P)
    {
        $this->key_P = $key_P;

        return $this;
    }

    /**
     * Get key_P
     *
     * @return int
     */
    public function getKeyP()
    {
        return $this->key_P;
    }

    /**
     * Set key_Tilde
     *
     * @param integer $key_Tilde
     *
     * @return KeyStroke
     */
    public function setKeyTilde($key_Tilde)
    {
        $this->key_Tilde = $key_Tilde;

        return $this;
    }

    /**
     * Get key_Tilde
     *
     * @return int
     */
    public function getKeyTilde()
    {
        return $this->key_Tilde;
    }

    /**
     * Set key_A
     *
     * @param integer $key_A
     *
     * @return KeyStroke
     */
    public function setKeyA($key_A)
    {
        $this->key_A = $key_A;

        return $this;
    }

    /**
     * Get key_A
     *
     * @return int
     */
    public function getKeyA()
    {
        return $this->key_A;
    }

    /**
     * Set key_S
     *
     * @param integer $key_S
     *
     * @return KeyStroke
     */
    public function setKeyS($key_S)
    {
        $this->key_S = $key_S;

        return $this;
    }

    /**
     * Get key_S
     *
     * @return int
     */
    public function getKeyS()
    {
        return $this->key_S;
    }

    /**
     * Set key_D
     *
     * @param integer $key_D
     *
     * @return KeyStroke
     */
    public function setKeyD($key_D)
    {
        $this->key_D = $key_D;

        return $this;
    }

    /**
     * Get key_D
     *
     * @return int
     */
    public function getKeyD()
    {
        return $this->key_D;
    }

    /**
     * Set key_F
     *
     * @param integer $key_F
     *
     * @return KeyStroke
     */
    public function setKeyF($key_F)
    {
        $this->key_F = $key_F;

        return $this;
    }

    /**
     * Get key_F
     *
     * @return int
     */
    public function getKeyF()
    {
        return $this->key_F;
    }

    /**
     * Set key_G
     *
     * @param integer $key_G
     *
     * @return KeyStroke
     */
    public function setKeyG($key_G)
    {
        $this->key_G = $key_G;

        return $this;
    }

    /**
     * Get key_G
     *
     * @return int
     */
    public function getKeyG()
    {
        return $this->key_G;
    }

    /**
     * Set key_H
     *
     * @param integer $key_H
     *
     * @return KeyStroke
     */
    public function setKeyH($key_H)
    {
        $this->key_H = $key_H;

        return $this;
    }

    /**
     * Get key_H
     *
     * @return int
     */
    public function getKeyH()
    {
        return $this->key_H;
    }

    /**
     * Set key_J
     *
     * @param integer $key_J
     *
     * @return KeyStroke
     */
    public function setKeyJ($key_J)
    {
        $this->key_J = $key_J;

        return $this;
    }

    /**
     * Get key_J
     *
     * @return int
     */
    public function getKeyJ()
    {
        return $this->key_J;
    }

    /**
     * Set key_K
     *
     * @param integer $key_K
     *
     * @return KeyStroke
     */
    public function setKeyK($key_K)
    {
        $this->key_K = $key_K;

        return $this;
    }

    /**
     * Get key_K
     *
     * @return int
     */
    public function getKeyK()
    {
        return $this->key_K;
    }

    /**
     * Set key_L
     *
     * @param integer $key_L
     *
     * @return KeyStroke
     */
    public function setKeyL($key_L)
    {
        $this->key_L = $key_L;

        return $this;
    }

    /**
     * Get key_L
     *
     * @return int
     */
    public function getKeyL()
    {
        return $this->key_L;
    }

    /**
     * Set key_OE
     *
     * @param integer $key_OE
     *
     * @return KeyStroke
     */
    public function setKeyOE($key_OE)
    {
        $this->key_OE = $key_OE;

        return $this;
    }

    /**
     * Get key_OE
     *
     * @return int
     */
    public function getKeyOE()
    {
        return $this->key_OE;
    }

    /**
     * Set key_AE
     *
     * @param integer $key_AE
     *
     * @return KeyStroke
     */
    public function setKeyAE($key_AE)
    {
        $this->key_AE = $key_AE;

        return $this;
    }

    /**
     * Get key_AE
     *
     * @return int
     */
    public function getKeyAE()
    {
        return $this->key_AE;
    }

    /**
     * Set key_Multiply
     *
     * @param integer $key_Multiply
     *
     * @return KeyStroke
     */
    public function setKeyMultiply($key_Multiply)
    {
        $this->key_Multiply = $key_Multiply;

        return $this;
    }

    /**
     * Get key_Multiply
     *
     * @return int
     */
    public function getKeyMultiply()
    {
        return $this->key_Multiply;
    }

    /**
     * Set key_Z
     *
     * @param integer $key_Z
     *
     * @return KeyStroke
     */
    public function setKeyZ($key_Z)
    {
        $this->key_Z = $key_Z;

        return $this;
    }

    /**
     * Get key_Z
     *
     * @return int
     */
    public function getKeyZ()
    {
        return $this->key_Z;
    }

    /**
     * Set key_X
     *
     * @param integer $key_X
     *
     * @return KeyStroke
     */
    public function setKeyX($key_X)
    {
        $this->key_X = $key_X;

        return $this;
    }

    /**
     * Get key_X
     *
     * @return int
     */
    public function getKeyX()
    {
        return $this->key_X;
    }

    /**
     * Set key_C
     *
     * @param integer $key_C
     *
     * @return KeyStroke
     */
    public function setKeyC($key_C)
    {
        $this->key_C = $key_C;

        return $this;
    }

    /**
     * Get key_C
     *
     * @return int
     */
    public function getKeyC()
    {
        return $this->key_C;
    }

    /**
     * Set key_V
     *
     * @param integer $key_V
     *
     * @return KeyStroke
     */
    public function setKeyV($key_V)
    {
        $this->key_V = $key_V;

        return $this;
    }

    /**
     * Get key_V
     *
     * @return int
     */
    public function getKeyV()
    {
        return $this->key_V;
    }

    /**
     * Set key_B
     *
     * @param integer $key_B
     *
     * @return KeyStroke
     */
    public function setKeyB($key_B)
    {
        $this->key_B = $key_B;

        return $this;
    }

    /**
     * Get key_B
     *
     * @return int
     */
    public function getKeyB()
    {
        return $this->key_B;
    }

    /**
     * Set key_N
     *
     * @param integer $key_N
     *
     * @return KeyStroke
     */
    public function setKeyN($key_N)
    {
        $this->key_N = $key_N;

        return $this;
    }

    /**
     * Get key_N
     *
     * @return int
     */
    public function getKeyN()
    {
        return $this->key_N;
    }

    /**
     * Set key_M
     *
     * @param integer $key_M
     *
     * @return KeyStroke
     */
    public function setKeyM($key_M)
    {
        $this->key_M = $key_M;

        return $this;
    }

    /**
     * Get key_M
     *
     * @return int
     */
    public function getKeyM()
    {
        return $this->key_M;
    }

    /**
     * Set key_Comma
     *
     * @param integer $key_Comma
     *
     * @return KeyStroke
     */
    public function setKeyComma($key_Comma)
    {
        $this->key_Comma = $key_Comma;

        return $this;
    }

    /**
     * Get key_Comma
     *
     * @return int
     */
    public function getKeyComma()
    {
        return $this->key_Comma;
    }

    /**
     * Set key_Period
     *
     * @param integer $key_Period
     *
     * @return KeyStroke
     */
    public function setKeyPeriod($key_Period)
    {
        $this->key_Period = $key_Period;

        return $this;
    }

    /**
     * Get key_Period
     *
     * @return int
     */
    public function getKeyPeriod()
    {
        return $this->key_Period;
    }

    /**
     * Set key_Minus
     *
     * @param integer $key_Minus
     *
     * @return KeyStroke
     */
    public function setKeyMinus($key_Minus)
    {
        $this->key_Minus = $key_Minus;

        return $this;
    }

    /**
     * Get key_Minus
     *
     * @return int
     */
    public function getKeyMinus()
    {
        return $this->key_Minus;
    }

    /**
     * Set key_RightCtrl
     *
     * @param integer $key_RightCtrl
     *
     * @return KeyStroke
     */
    public function setKeyRightCtrl($key_RightCtrl)
    {
        $this->key_RightCtrl = $key_RightCtrl;

        return $this;
    }

    /**
     * Get key_RightCtrl
     *
     * @return int
     */
    public function getKeyRightCtrl()
    {
        return $this->key_RightCtrl;
    }

    /**
     * Set key_Enter
     *
     * @param integer $key_Enter
     *
     * @return KeyStroke
     */
    public function setKeyEnter($key_Enter)
    {
        $this->key_Enter = $key_Enter;

        return $this;
    }

    /**
     * Get key_Enter
     *
     * @return int
     */
    public function getKeyEnter()
    {
        return $this->key_Enter;
    }

    /**
     * Set key_RightShift
     *
     * @param integer $key_RightShift
     *
     * @return KeyStroke
     */
    public function setKeyRightShift($key_RightShift)
    {
        $this->key_RightShift = $key_RightShift;

        return $this;
    }

    /**
     * Get key_RightShift
     *
     * @return int
     */
    public function getKeyRightShift()
    {
        return $this->key_RightShift;
    }

    /**
     * Set key_Insert
     *
     * @param integer $key_Insert
     *
     * @return KeyStroke
     */
    public function setKeyInsert($key_Insert)
    {
        $this->key_Insert = $key_Insert;

        return $this;
    }

    /**
     * Get key_Insert
     *
     * @return int
     */
    public function getKeyInsert()
    {
        return $this->key_Insert;
    }

    /**
     * Set key_Home
     *
     * @param integer $key_Home
     *
     * @return KeyStroke
     */
    public function setKeyHome($key_Home)
    {
        $this->key_Home = $key_Home;

        return $this;
    }

    /**
     * Get key_Home
     *
     * @return int
     */
    public function getKeyHome()
    {
        return $this->key_Home;
    }

    /**
     * Set key_PageUp
     *
     * @param integer $key_PageUp
     *
     * @return KeyStroke
     */
    public function setKeyPageUp($key_PageUp)
    {
        $this->key_PageUp = $key_PageUp;

        return $this;
    }

    /**
     * Get key_PageUp
     *
     * @return int
     */
    public function getKeyPageUp()
    {
        return $this->key_PageUp;
    }

    /**
     * Set key_PageDown
     *
     * @param integer $key_PageDown
     *
     * @return KeyStroke
     */
    public function setKeyPageDown($key_PageDown)
    {
        $this->key_PageDown = $key_PageDown;

        return $this;
    }

    /**
     * Get key_PageDown
     *
     * @return int
     */
    public function getKeyPageDown()
    {
        return $this->key_PageDown;
    }

    /**
     * Set key_Delete
     *
     * @param integer $key_Delete
     *
     * @return KeyStroke
     */
    public function setKeyDelete($key_Delete)
    {
        $this->key_Delete = $key_Delete;

        return $this;
    }

    /**
     * Get key_Delete
     *
     * @return int
     */
    public function getKeyDelete()
    {
        return $this->key_Delete;
    }

    /**
     * Set key_End
     *
     * @param integer $key_End
     *
     * @return KeyStroke
     */
    public function setKeyEnd($key_End)
    {
        $this->key_End = $key_End;

        return $this;
    }

    /**
     * Get key_End
     *
     * @return int
     */
    public function getKeyEnd()
    {
        return $this->key_End;
    }

    /**
     * Set key_Left
     *
     * @param integer $key_Left
     *
     * @return KeyStroke
     */
    public function setKeyLeft($key_Left)
    {
        $this->key_Left = $key_Left;

        return $this;
    }

    /**
     * Get key_Left
     *
     * @return int
     */
    public function getKeyLeft()
    {
        return $this->key_Left;
    }

    /**
     * Set key_Up
     *
     * @param integer $key_Up
     *
     * @return KeyStroke
     */
    public function setKeyUp($key_Up)
    {
        $this->key_Up = $key_Up;

        return $this;
    }

    /**
     * Get key_Up
     *
     * @return int
     */
    public function getKeyUp()
    {
        return $this->key_Up;
    }

    /**
     * Set key_Right
     *
     * @param integer $key_Right
     *
     * @return KeyStroke
     */
    public function setKeyRight($key_Right)
    {
        $this->key_Right = $key_Right;

        return $this;
    }

    /**
     * Get key_Right
     *
     * @return int
     */
    public function getKeyRight()
    {
        return $this->key_Right;
    }

    /**
     * Set key_Down
     *
     * @param integer $key_Down
     *
     * @return KeyStroke
     */
    public function setKeyDown($key_Down)
    {
        $this->key_Down = $key_Down;

        return $this;
    }

    /**
     * Get key_Down
     *
     * @return int
     */
    public function getKeyDown()
    {
        return $this->key_Down;
    }

    /**
     * Set key_NumLock
     *
     * @param integer $key_NumLock
     *
     * @return KeyStroke
     */
    public function setKeyNumLock($key_NumLock)
    {
        $this->key_NumLock = $key_NumLock;

        return $this;
    }

    /**
     * Get key_NumLock
     *
     * @return int
     */
    public function getKeyNumLock()
    {
        return $this->key_NumLock;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return KeyStroke
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set pasted
     *
     * @param integer $pasted
     *
     * @return KeyStroke
     */
    public function setPasted($pasted)
    {
        $this->total = $pasted;

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
     * Set key down time
     *
     * @param float $keyDownTime
     *
     * @return KeyStroke
     */
    public function setKeyDownTime($keyDownTime)
    {
        $this->keyDownTime = $keyDownTime;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getKeyDownTime()
    {
        return $this->keyDownTime;
    }
}
