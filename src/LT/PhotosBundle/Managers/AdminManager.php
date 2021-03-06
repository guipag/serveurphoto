<?php

namespace LT\PhotosBundle\Managers;

use Doctrine\ORM\EntityManager;
use LT\PhotosBundle\Entity\Event;

class AdminManager {
  protected $em;

  public function __construct(\Doctrine\ORM\EntityManager $em)
  {
    $this->em = $em;
  }

  public function asPhotosToValidate(Event $event) {
    $repository = $this->em->getRepository('LTPhotosBundle:Photo');

    $nbrPhotoToValidate = $repository->createQueryBuilder('a')
                                    ->select('COUNT(a)')
                                    ->where('a.event = :event')
				    ->andWhere('a.valid = :valid')
                                    ->setParameter('event', $event)
                                    ->setParameter('valid', false)
                                ->getQuery()
                                ->getSingleScalarResult()
                        ;
    return $nbrPhotoToValidate;
  }
}









