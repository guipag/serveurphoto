<?php

namespace LT\PhotosBundle\Services;

use LT\PhotosBundle\Managers\PhotoManager;

class PhotoExtension extends \Twig_Extension {
  protected $manager;

  public function __construct(PhotoManager $manager) {
    $this->manager = $manager;
  }

  public function getFunctions() {
    return array(
	new \Twig_SimpleFunction('cover', array($this, 'coverFunction')),
		);
  }

  public function coverFunction($event, $category = null) {
    //fonction qui retourne la photo de couverture
    return $this->manager->getCover($event, $category);
  }

  public function getName() {
    return 'photo_extension';
  }
}
