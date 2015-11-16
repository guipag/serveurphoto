<?php

/*
Le comportement sluggable est mis en place, il faut donc enlever les getter/setter du slug et les supprimer des formulaires
*/


namespace LT\PhotosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\ElasticaBundle\Configuration\Search;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LT\PhotosBundle\Entity\EventRepository")
 * @Search(repositoryClass="LT\PhotosBundle\Entity\SearchRepository\EventRepository")
 */
class Event
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
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date", nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\OneToMany(targetEntity="LT\PhotosBundle\Entity\Photo", cascade={"persist", "remove"}, mappedBy="event")
     */
    private $photos;

    /**
     * @ORM\ManyToMany(targetEntity="LT\PhotosBundle\Entity\Photograph", cascade={"persist"}, inversedBy="events")
     * @ORM\JoinColumn(nullable=true)
     */
    private $photographs;

    /**
     * @ORM\OneToMany(targetEntity="LT\PhotosBundle\Entity\Category", cascade={"persist", "remove"}, mappedBy="event", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    private $categories;

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

    public function __construct() {
	$this->photos      = new ArrayCollection();
	$this->photographs = new ArrayCollection();
	$this->categories  = new ArrayCollection();
    }

    public function addPhoto(Photo $photo) {
	$this->photos[] = $photo;
	$photo->setEvent($this);

	return $this;
    }

    public function setPhotos(ArrayCollection $photos) {
	foreach ($photos as $photo) {
	    $this->addPhoto($photo);
	}
    }

    public function removePhoto(Photo $photo) {
	$this->photos->removeElement($photo);
    }

    public function getPhotos() {
	return $this->photos;
    }

    public function addCategory(Category $category) {
        $this->categories[] = $category;
        $category->setEvent($this);

        return $this;
    }

    public function setCategories(ArrayCollection $categories) {
        foreach ($categoriess as $category) {
            $this->addCategory($category);
        }

	return $this;
    }

    public function removeCategory(Category $category) {
        $this->categories->removeElement($category);
	$category->removeEvent();
    }

    public function getCategories() {
        return $this->categories;
    }

    public function addPhotograph(Photograph $photograph) {
	$this->photographs[] = $photograph;
	$photograph->addEvent($this);

	return $this;
    }

    public function removePhotograph(Photograph $photograph) {
	$this->photographs->removeElement($photograph);
	$photograph->removeEvent($this);
    }

    public function getPhotographs() {
	return $this->photographs;
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
     * @return Event
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
     * Set slug
     *
     * @param string $slug
     * @return Event
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
     * Set date
     *
     * @param \DateTime $date
     * @return Event
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    public function getCreatedAt() {
      return created_at;
    }

    public function getUpdatedAt() {
      return updated_at;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Event
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Event
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
     * @return Event
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }
}
