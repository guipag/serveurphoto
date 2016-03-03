<?php

//ajouter la gestion des licences

namespace LT\PhotosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Photograph
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LT\PhotosBundle\Entity\PhotographRepository")
 */
class Photograph
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
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=255)
     */
    private $nickname;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"nickname"})
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="LT\PhotosBundle\Entity\Event", mappedBy="photographs", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $events;

    /**
     * @ORM\OneToOne(targetEntity="LT\UserBundle\Entity\User", inversedBy="photograph", cascade={"persist"})
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="licence", type="string", length=255, nullable=true)
     */
    private $licence;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date")
     * @Gedmo\Timestampable(on="create")
     */
    private $created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="date")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated_at;

    /**
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean", nullable=true)
     */
    private $locked = false;

    public function __construct() {
	$this->events = new ArrayCollection();
    }

    public function addEvent(Event $event) {
	$this->events[] = $event;

	return $this;
    }

    public function removeEvent(Event $event) {
	$this->events->removeElement($event);
    }

    public function getEvents() {
	return $this->events;
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
     * @return Photograph
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
     * Set firstname
     *
     * @param string $firstname
     * @return Photograph
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
     * Set nickname
     *
     * @param string $nickname
     * @return Photograph
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
     * Set description
     *
     * @param string $description
     * @return Photograph
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set user
     *
     * @param \LT\UserBundle\Entity\User $user
     * @return Photograph
     */
    public function setUser(\LT\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \LT\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getCreatedAt() {
      return created_at;
    }

    public function getUpdatedAt() {
      return updated_at;
    }


    /**
     * Set slug
     *
     * @param string $slug
     * @return Photograph
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Photograph
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Photograph
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Set licence
     *
     * @param string $licence
     * @return Photograph
     */
    public function setLicence($licence)
    {
        $this->licence = $licence;

        return $this;
    }

    /**
     * Get licence
     *
     * @return string 
     */
    public function getLicence()
    {
        return $this->licence;
    }

    public function isLocked() {
	return $this->locked;
    }

    public function setLocked($lock) {
	$this->locked = $lock;

	return $this;
    }
}
