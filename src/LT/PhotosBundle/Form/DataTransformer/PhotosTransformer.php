<?php

namespace LT\PhotosBUndle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LT\PhotosBundle\Entity\Photo;

class PhotosTransformer implements DataTransformerInterface {
  private $om;

  public function __construct(ObjectManager $om) {
    $this->om = $om;
  }

  public function transform($photos) {

  }

  public function reverseTransform($files) {
    $photos = [];
    foreach ($files as $file) {
      $photo = new Photo();
	//lier le fichier à la photo
      $photos[] = $photo;
    }
    return $photos;
  }
}
