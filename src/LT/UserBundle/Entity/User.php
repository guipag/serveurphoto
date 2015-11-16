<?php

namespace LT\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User extends BaseUser {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** 
     * @ORM\OneToOne(targetEntity="LT\PhotosBundle\Entity\Photograph", mappedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $photograph;

    /**
     * @var boolean
     *
     * @ORM\Column(name="followed", type="boolean")
     */
    private $followed = false;

    /**
     * Set photograph
     *
     * @param \LT\PhotosBundle\Entity\Photograph $photograph
     * @return User
     */
    public function setPhotograph(\LT\PhotosBundle\Entity\Photograph $photograph)
    {
        $this->photograph = $photograph;
        $this->photograph->setUser($this);

        return $this;
    }

    /**
     * Get photograph
     *
     * @return \LT\PhotosBundle\Entity\Photograph 
     */
    public function getPhotograph()
    {
        return $this->photograph;
    }

    /**
     * Is followed
     *
     * @return boolean
     */
    public function isFollowed() {
          return $this->followed;
    }

    /**
     * Set followed
     *
     * @return User
     */
    public function setFollowed($followed) {
        $this->followed = $followed;

        return $this;
    }
}
