<?php

namespace Ydzy\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Area
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ydzy\ApiBundle\Entity\AreaRepository")
 */
class Area
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="upid", type="integer")
     */
    private $upid;

    /**
     * @var string
     *
     * @ORM\Column(name="p_name", type="string", length=255)
     */
    private $pName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="updated_date", type="string", length=255)
     */
    private $updatedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set upid
     *
     * @param integer $upid
     * @return Area
     */
    public function setUpid($upid)
    {
        $this->upid = $upid;
    
        return $this;
    }

    /**
     * Get upid
     *
     * @return integer 
     */
    public function getUpid()
    {
        return $this->upid;
    }

    /**
     * Set pName
     *
     * @param string $pName
     * @return Area
     */
    public function setPName($pName)
    {
        $this->pName = $pName;
    
        return $this;
    }

    /**
     * Get pName
     *
     * @return string 
     */
    public function getPName()
    {
        return $this->pName;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Area
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    
        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set updatedDate
     *
     * @param string $updatedDate
     * @return Area
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    
        return $this;
    }

    /**
     * Get updatedDate
     *
     * @return string 
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Area
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
