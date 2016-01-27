<?php

namespace LT\PhotosBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use LT\PhotosBundle\Entity\FormContact;
use LT\PhotosBundle\Entity\Event;
use LT\PhotosBundle\Entity\Photograph;
use LT\PhotosBundle\Entity\Category;

class ElasticController extends Controller {
  public function searchAction($request) {
    //controller de recherche de quelque chose depuis la barre de navigation
    return $this->render('LTPhotosBundle:Elastic:search.html.twig');
  }
}
