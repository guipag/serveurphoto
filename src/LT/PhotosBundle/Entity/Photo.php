<?php

namespace LT\PhotosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Asset;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Photo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LT\PhotosBundle\Entity\PhotoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Photo
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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var boolean
     *
     * @ORM\Column(name="valid", type="boolean", nullable=true)
     */
    private $valid = false;

    /**
     * @ORM\ManyToOne(targetEntity="LT\PhotosBundle\Entity\Event", inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="LT\PhotosBundle\Entity\Photograph")
     * @ORM\JoinColumn(nullable=false)
     */
    private $photograph;

    /**
     * @ORM\ManyToOne(targetEntity="LT\PhotosBundle\Entity\Category", inversedBy="photos")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @var boolean
     *
     * @ORM\Column(name="censured", type="boolean")
     */
    private $censured = false;

    /**
     * @ORM\ManyToMany(targetEntity="LT\PhotosBundle\Entity\Tag", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $tags;

    /**
     * @Asset\File(maxSize="20000000")
     */
    private $file;

    private $tempFilename;

    /**
     * @var string
     *
     * @ORM\Column(name="originalname", type="string", length=255)
     */
    private $originalName;

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
	$this->tags = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->file) {
	    $this->originalName = $this->file->getClientOriginalName();
            $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
	if (null === $this->file) return;

	$this->file->move($this->getUploadRootDir(), $this->path);

	unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
	if ($file = $this->getAbsolutePath()) {
	    unlink($file);
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
     * Set path
     *
     * @param string $path
     * @return Photo
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set valid
     *
     * @param boolean $valid
     * @return Photo
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
    
        return $this;
    }

    /**
     * Get valid
     *
     * @return boolean 
     */
    public function getValid()
    {
        return $this->valid;
    }

    public function setEvent(Event $event) {
	$this->event = $event;
    }

    public function getEvent() {
	return $this->event;
    }

    public function setPhotograph(Photograph $photograph) {
	$this->photograph = $photograph;
    }

    public function getPhotograph() {
	return $this->photograph;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
	$this->file = $file;
	if (null !== $this->path) {
	    $this->tempFilename = $this->path;
	    $this->path = null;
	}
    }

    public function getFile() {
	return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return '/var/www/photos/web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'images';
    }

    public function getCreatedAt() {
      return created_at;
    }

    public function getUpdatedAt() {
      return updated_at;
    }


    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Photo
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
     * @return Photo
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get censured
     *
     * @return boolean
     */
    public function getCensured() {
      return $this->censured;
    }

    public function isCensured() {
      return $this->censured;
    }

    /**
     * Set censured
     *
     * @param boolean $censured
     */
    public function setCensured($censured) {
      $this->censured = $censured;
    }

    /**
     * Set category
     *
     * @param \LT\PhotosBundle\Entity\Category $category
     * @return Photo
     */
    public function setCategory(\LT\PhotosBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \LT\PhotosBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
    public function getOriginalName() {
        return $this->originalName;
    }
    public function setOriginalName($originalName) {
	$this->originalName = $originalName;
	return $this;
    }

    public function addTag(Tag $tag) {
	$this->tags[] = $tag;
	return $this;
    }

    public function removeTag(Tag $tag) {
	$this->tags->remove($tag);
    }

    public function getTags() {
	return $this->tags;
    }
}
