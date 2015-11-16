<?php
// src/LT/PhotosBundle/NotifMail/PhotosPostEvent.php

namespace LT\PhotosBundle\NotifMail;

use Symfony\Component\EventDispatcher\Event as EventDispatcher;
use LT\PhotosBundle\Entity\Event;
use LT\PhotosBundle\Entity\Photograph;

class PhotosPostEvent extends EventDispatcher
{
  protected $event;
  protected $photograph;

  public function __construct(Event $event, Photograph $photograph)
  {
    $this->event	 = $event;
    $this->photograph    = $photograph;
  }

  public function getEvent()
  {
    return $this->event;
  }

  public function getPhotograph()
  {
    return $this->photograph;
  }
}
