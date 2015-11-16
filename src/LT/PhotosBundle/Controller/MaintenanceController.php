<?php

namespace LT\PhotosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use LT\PhotosBundle\Entity\FormContact;
use LT\PhotosBundle\Entity\Event;
use LT\PhotosBundle\Entity\Photograph;
use LT\PhotosBundle\Entity\Category;

class MaintenanceController extends Controller
{
    public function indexAction() {
        return $this->render('LTPhotosBundle:Maintenance:index.html.twig');
    }

    public function fusionPhotographAction(Request $request) {
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('message', 'textarea')
            ->add('send', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
        }

	return $this->render('LTPhotosBundle:Maintenance:fusionPhotograph.html.twig');
    }
}
