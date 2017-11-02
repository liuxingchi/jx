<?php

namespace ydzy\apiBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ydzy\apiBundle\Entity\PromoImgs;

/**
 * promos
 *
 * @ORM\Table(name="promos")
 * @ORM\Entity
 */
class Promos
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="descrption", type="text")
     */
    private $descrption;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal")
     */
    private $price;
    
    /**
   * @ORM\OneToMany(targetEntity="PromoImgs", mappedBy="Promos")
   */
    private $imgs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $create_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime")
     */
    private $update_date;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", length=11)
     */
    private $status;

    public function __construct()
    {
        {
            $this->imgs = new ArrayCollection();
        }
    }
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
     * Set name
     *
     * @param string $name
     * @return promos
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
     * Set descrption
     *
     * @param string $descrption
     * @return promos
     */
    public function setDescrption($descrption)
    {
        $this->descrption = $descrption;

        return $this;
    }

    /**
     * Get descrption
     *
     * @return string 
     */
    public function getDescrption()
    {
        return $this->descrption;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return promos
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set imgs
     *
     * @param array $imgs
     * @return promos
     */
    public function setImgs($imgs)
    {
        $this->imgs = $imgs;

        return $this;
    }

    /**
     * Get imgs
     *
     * @return array 
     */
    public function getImgs()
    {
        return $this->imgs;
    }

    /**
     * Set create_date
     *
     * @param \DateTime $createDate
     * @return promos
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get create_date
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set update_date
     *
     * @param \DateTime $updateDate
     * @return promos
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get update_date
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * Set status
     *
     * @param \integer $status
     * @return promos
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add imgs
     *
     * @param \ydzy\apiBundle\Entity\PromoImgs $imgs
     * @return Promos
     */
    public function addImg(\ydzy\apiBundle\Entity\PromoImgs $imgs)
    {
        $this->imgs[] = $imgs;

        return $this;
    }

    /**
     * Remove imgs
     *
     * @param \ydzy\apiBundle\Entity\PromoImgs $imgs
     */
    public function removeImg(\ydzy\apiBundle\Entity\PromoImgs $imgs)
    {
        $this->imgs->removeElement($imgs);
    }
}
