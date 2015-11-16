<?php

namespace LT\PhotosBundle\Listener;

use Doctrine\ORM\Event\LifeCycleEventArgs;
use LT\PhotosBundle\Entity\Photo;

class CacheImageListener {
  protected $cacheManager;

  public function __construct($cacheManager) {
    $this->cacheManager = $cacheManager;
  }

  public function postUpdate(LifeCycleEventArgs $args) {
    $entity = $args->getEntity();

    if ($entity instanceof Photo) {
      $this->cacheManager->remove($entity->getWebPath());
    }
  }

  public function preRemove(LifeCycleEventArgs $args) {
    $entity = $args->getEntity();

    if ($entity instanceof Photo) {
      $this->cacheManager->remove($entity->getWebPath());
    }
  }
}
