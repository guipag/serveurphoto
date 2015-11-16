<?php
// src/LT/PhotosBundle/NotifMail/SendMailListener.php

namespace LT\PhotosBundle\NotifMail;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use LT\PhotosBundle\NotifMail\PhotosPostEvent;

class SendMailListener
{
  protected $mailer;

  public function __construct(\Swift_Mailer $mailer) {
    $this->mailer = $mailer;
  }

  public function processSendMail(PhotosPostEvent $event)
  {
    $message = \Swift_Message::newInstance()
        ->setSubject('Nouvelles photos en ligne !')
        ->setFrom('moderation@photos.crans.org')
        ->setTo('photos@crans.org')
        ->setBody($event->getPhotograph()->getNickname() . " vient de déposer de nouvelles photos dans l'évènement " . $event->getEvent()->getName())
    ;

    $this->mailer->send($message);
  }
}
