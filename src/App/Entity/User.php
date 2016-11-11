<?php

namespace App\Entity;

use App\Naming\Right;
use App\Util\Password;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;
use Doctrine\ORM\Mapping as ORM;

/**
 * The entity class for the user model.
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends EntityBase
{
    /**
     * @var int
     *
     * @Groups({"list"})
     * @Type("integer")
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Type("string")
     *
     * @ORM\Column(name="token", type="string", length=6000)
     */
    private $token = '';

    /**
     * @var string
     *
     * @Groups({"list"})
     * @Type("string")
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname = '';

    /**
     * @var string
     *
     * @Groups({"list"})
     * @Type("string")
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname = '';

    /**
     * @var string
     *
     * @Groups({"list"})
     * @Type("string")
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email = '';

    /**
     * @var string
     *
     * @Type("string")
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @Groups({"list"})
     * @Type("string")
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @Groups({"list"})
     * @Type("string")
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone = '';

    /**
     * @ManyToMany(targetEntity="Permission", cascade={"all"})
     * @JoinTable(name="users_permissions", joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")})
     *
     * @Groups({"permissions"})
     * @Type("array")
     *
     * @var ArrayCollection
     */
    private $rights;

    /**
     * @OneToMany(targetEntity="Notification", mappedBy="user", cascade={"all"})
     *
     * @var ArrayCollection
     */
    private $notifications;

    /**
     * @OneToMany(targetEntity="SearchSetting", mappedBy="user", cascade={"all"})
     *
     * @var ArrayCollection
     */
    private $searchSettings;

    /**
     * @OneToOne(targetEntity="Settings")
     *
     * @var Settings
     */
    private $settings;

    /**
     * Plugin api key
     *
     * @var string
     *
     * @ORM\Column(name="apiKey", type="string", length=255)
     */
    private $apiKey;

    public function __construct()
    {
        $this->rights = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->searchSettings = new ArrayCollection();
    }

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
     * Set token
     *
     * @param   string  $token
     *
     * @return  User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = Password::hash($password);

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set rights.
     *
     * @param ArrayCollection $rights
     *
     * @return User
     */
    public function setRights($rights)
    {
        $this->rights = $rights;

        return $this;
    }

    /**
     * Get rights.
     *
     * @return ArrayCollection
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * Set settings.
     *
     * @param Settings $settings
     *
     * @return User
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Get settings.
     *
     * @return Settings
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Get notifications.
     *
     * @return ArrayCollection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Get search settings.
     *
     * @return ArrayCollection
     */
    public function getSearchSettings()
    {
        return $this->searchSettings;
    }

    /**
     * Set api key.
     *
     * @return User
     */
    public function refreshApiKey()
    {
        $this->apiKey = md5($this->getEmail() . microtime() . $this->getUsername());

        return $this;
    }

    /**
     * Get search settings.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Checks if the user is admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->rights->exists(function ($index, $right) {
            /**
             * @var Permission $right
             */
            return $right->getName() === Right::ADMIN;
        });
    }

    /**
     * Check if user has specified right.
     *
     * @param  string $right
     *
     * @return bool
     */
    public function hasRight($right)
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->rights->exists(function ($i, $p) use ($right) {
            /** @var Permission $p */
            return $p->getName() == $right;
        });
    }

    /**
     * Get full name
     *
     * @return string
     */
    public function getFullName()
    {
        return sprintf(
            '%1$s %2$s',
            /** 1 */ $this->getFirstname(),
            /** 2 */ $this->getLastname()
        );
    }
}
