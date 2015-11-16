<?php

namespace LT\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use LT\CoreBundle\Entity\FormContact;

class DefaultController extends Controller
{
    public function indexAction($name) {
        return $this->render('LTCoreBundle:Default:index.html.twig', array('name' => $name));
    }

    public function contactAction(Request $request) {
        $formcontact = new FormContact();
        $formBuilder = $this->get('form.factory')->createBuilder('form', $formcontact);

        $formBuilder
          ->add('name', 'text')
          ->add('mail', 'text')
          ->add('object', 'choice', array(
		'choices' => array('utilisation' => 'Utilisation du site', 'censure' => 'CENSURE'),
		'required' => true
		))
          ->add('content', 'textarea')
          ->add('send', 'submit')
        ;

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $message = \Swift_Message::newInstance()
                ->setSubject($data->getObject())
                ->setFrom($data->getMail())
                ->setTo('guipag@gmail.com')
                ->setBody($data->getContent());

            $this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add('notice', 'Le message a bien été envoyé.');
            return $this->redirect($this->generateUrl('lt_photos_contact'));
        }

        return $this->render('LTCoreBundle:Default:contact.html.twig', array('form' => $form->createView(),));
    }
}
