<?php
// src/Ydzy/UserBundle/Entity/User.php

namespace Ydzy\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string $nickname
     *
     * @ORM\Column(name="nickname", type="string", length=32)
     */
    protected $nickname;
	/**
     * @var string $truename
     *
     * @ORM\Column(name="truename", type="string", length=32)
     */
    protected $truename;
  	 /**
     * @var string $area
     *
     * @ORM\Column(name="area", type="string", length=32)
     */
    protected $area;
	 /**
     * @var string $bank
     *
     * @ORM\Column(name="bank", type="string", length=32)
     */
    protected $bank;
	 /**
     * @var string $bankAddress
     *
     * @ORM\Column(name="bankAddress", type="string", length=32)
     */
    protected $bankAddress;
	 /**
     * @var string $bankCard
     *
     * @ORM\Column(name="bankCard", type="string", length=32)
     */
    protected $bankCard;
	 /**
     * @var string $signature
     *
     * @ORM\Column(name="signature", type="string", length=255)
     */
    protected $signature;
	/**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="limitation", type="integer")
     */
    private $limitation;
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;
	
    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Set nickname
     *
     * @param string $nickname
     * @return User
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    
        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
	}

    /**
     * Set count
     *
     * @param integer $count
     * @return User
     */
    public function setCount($count)
    {
        $this->count = $count;
    
        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

   

    /**
     * Set signature
     *
     * @param string $signature
     * @return User
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
    
        return $this;
    }

    /**
     * Get signature
     *
     * @return string 
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set limitation
     *
     * @param integer $limitation
     * @return User
     */
    public function setLimitation($limitation)
    {
        $this->limitation = $limitation;
    
        return $this;
    }

    /**
     * Get limitation
     *
     * @return integer 
     */
    public function getLimitation()
    {
        return $this->limitation;
    }

    /**
     * Set truename
     *
     * @param string $truename
     * @return User
     */
    public function setTruename($truename)
    {
        $this->truename = $truename;
    
        return $this;
    }

    /**
     * Get truename
     *
     * @return string 
     */
    public function getTruename()
    {
        return $this->truename;
    }

    /**
     * Set area
     *
     * @param string $area
     * @return User
     */
    public function setArea($area)
    {
        $this->area = $area;
    
        return $this;
    }

    /**
     * Get area
     *
     * @return string 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set bank
     *
     * @param string $bank
     * @return User
     */
    public function setBank($bank)
    {
        $this->bank = $bank;
    
        return $this;
    }

    /**
     * Get bank
     *
     * @return string 
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set bankAddress
     *
     * @param string $bankAddress
     * @return User
     */
    public function setBankAddress($bankAddress)
    {
        $this->bankAddress = $bankAddress;
    
        return $this;
    }

    /**
     * Get bankAddress
     *
     * @return string 
     */
    public function getBankAddress()
    {
        return $this->bankAddress;
    }

    /**
     * Set bankCard
     *
     * @param string $bankCard
     * @return User
     */
    public function setBankCard($bankCard)
    {
        $this->bankCard = $bankCard;
    
        return $this;
    }

    /**
     * Get bankCard
     *
     * @return string 
     */
    public function getBankCard()
    {
        return $this->bankCard;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return User
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
}