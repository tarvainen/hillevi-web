<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use JMS\Serializer\Annotation\Type;

/**
 * ApiReader
 *
 * @ORM\Table(name="api_reader")
 * @ORM\Entity(repositoryClass="App\Repository\ApiReaderRepository")
 */
class ApiReader extends EntityBase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Type("integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="tableName", type="string", length=255, unique=true)
     * @Type("string")
     */
    private $table;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Type("string")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=6000)
     * @Type("string")
     */
    private $url;

    /**
     * @var array
     *
     * @ORM\Column(name="columns", type="array")
     * @Type("array")
     */
    private $columns;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastUpdate", type="datetime")
     * @Type("DateTime")
     */
    private $lastUpdate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     * @Type("boolean")
     */
    private $active;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     */
    private $owner;

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
     * Set name
     *
     * @param string $name
     *
     * @return ApiReader
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
     * Set table
     *
     * @param string $table
     *
     * @return ApiReader
     */
    public function setTableName($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get table
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->table;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return ApiReader
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return ApiReader
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set columns
     *
     * @param array $columns
     *
     * @return ApiReader
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Get columns
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     *
     * @return ApiReader
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Set owner
     *
     * @param User $owner
     *
     * @return ApiReader
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set activeness.
     *
     * @param   boolean $active
     *
     * @return  ApiReader
     */
    public function setActive($active)
    {
        if (is_string($active)) {
            $this->active = $active === 'active';
        } else {
            $this->active = $active;
        }

        return $this;
    }

    /**
     * Get activeness.
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}
