<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * SearchSetting
 *
 * @ORM\Table(name="search_setting")
 * @ORM\Entity(repositoryClass="App\Repository\SearchSettingRepository")
 */
class SearchSetting
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
     * @var string
     *
     * @ORM\Column(name="module", type="string", length=255)
     */
    private $module;

    /**
     * @var string
     *
     * @ORM\Column(name="setting", type="string", length=99999)
     */
    private $setting;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="User")
     *
     * @var User
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
     * Set module
     *
     * @param string $module
     *
     * @return SearchSetting
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set setting
     *
     * @param string $setting
     *
     * @return SearchSetting
     */
    public function setSetting($setting)
    {
        $this->setting = $setting;

        return $this;
    }

    /**
     * Get setting
     *
     * @return string
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SearchSetting
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the user
     *
     * @param  User $user
     *
     * @return SearchSetting
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
}
