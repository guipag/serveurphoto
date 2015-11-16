<?php

namespace LT\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LTNewsletterBundle:Default:index.html.twig', array('name' => $name));
    }
}
