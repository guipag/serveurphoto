<?php

namespace LT\PhotosBundle\Listener;

use Oneup\UploaderBundle\Event\PostPersistEvent;

class UploadListener {
  public function __construct($doctrine) {
    $this->doctrine = $doctrine;
  }

  public function onUpload(PostPersistEvent $event) {

  }
}
