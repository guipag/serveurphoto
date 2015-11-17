<?php

namespace LT\PhotosBundle\Managers;

use Doctrine\ORM\EntityManager;
use LT\PhotosBundle\Entity\Photo;
use LT\PhotosBundle\Entity\Event;
use LT\PhotosBundle\Entity\Category;

class PhotoManager {
  protected $em;

  public function __construct(\Doctrine\ORM\EntityManager $em)
  {
    $this->em = $em;
  }

  public function getCover(Event $event, Category $category = null) {
    $photo = $this->em->getRepository('LTPhotosBundle:Photo')->getOnePhotoViewable($event, $category);

    if ($photo === null) {
      $photo = new Photo();
    }

    return $photo;
  }
}
