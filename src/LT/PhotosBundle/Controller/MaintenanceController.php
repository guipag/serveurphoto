<?php

namespace LT\PhotosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

    public function censuredPhotosAction(Request $request) {
	$repo = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Photo');

	$photos = $repo->findByCensured(true);

	return $this->render('LTPhotosBundle:Maintenance:deleteCensuredPhotos.html.twig', array('photos' => $photos));
    }

    public function deleteCensuredPhotosAction(Request $request) {
        $repo = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Photo');
	$em = $this->getDoctrine()->getManager();

        $photos = $repo->findByCensured(true);

	foreach ($photos as $photo)
            $em->remove($photo);

	$em->flush();

	return $this->redirectToRoute('maintenance_censured');
    }

}
