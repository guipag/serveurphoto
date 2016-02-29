<?php

namespace LT\PhotosBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ElasticController extends Controller {
  public function searchAction($request) {
    //controller de recherche de quelque chose depuis la barre de navigation
    return $this->render('LTPhotosBundle:Elastic:search.html.twig');
  }
}
