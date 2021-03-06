<?php

namespace LT\PhotosBundle\Services;

use LT\PhotosBundle\Managers\AdminManager;

class AdminExtension extends \Twig_Extension {
  protected $manager;

  public function __construct(AdminManager $manager) {
    $this->manager = $manager;
  }

  public function getFunctions() {
    return array(
	new \Twig_SimpleFunction('asPhotosToValidate', array($this, 'asPhotosToValidateFunction')),
                );
  }

  public function asPhotosToValidateFunction($event) {
    return $this->manager->asPhotosToValidate($event);
  }

  public function getName() {
    return 'admin_extension';
  }
}

