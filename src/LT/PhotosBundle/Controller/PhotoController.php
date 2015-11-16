<?php

namespace LT\PhotosBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use LT\PhotosBundle\Entity\Photo;
use LT\PhotosBundle\Entity\Event;
use LT\PhotosBundle\Entity\Photograph;
use LT\PhotosBundle\Entity\Category;
use LT\PhotosBundle\Form\PhotoType;
use LT\PhotosBundle\Form\EventType;
use LT\PhotosBundle\NotifMail\NotifMailEvents;
use LT\PhotosBundle\NotifMail\PhotosPostEvent;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class PhotoController extends Controller {
    public function uploadAction(Request $request) {
        $photo = new Photo();

        $photograph = $this->getUser()->getPhotograph();

        $photo->setPhotograph($photograph);

        $form = $this->get('form.factory')->create(new PhotoType, $photo);

        //création d'un évènement
        $new_event = new Event();
        $form_event = $this->createForm(new EventType(), $new_event, array(
                    'action' => $this->generateUrl('event_create'),
                    'method' => 'POST',
        ));

        $form_event->add('submit', 'submit', array('label' => 'Créer'));
        //formulaire de création d'un nouvel event

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if (!in_array($photograph, $photo->getEvent()->getPhotographs()->toArray()))
                $photo->getEvent()->addPhotograph($photo->getPhotograph());

            $em->persist($photo);
            $em->flush();
            //rediriger vers une page
        }

        $events = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Event')->findAllDateDesc();

	$datatable = $this->get('app.datatable.event');
    	$datatable->buildDatatable();

        return $this->render('LTPhotosBundle:Default:upload.html.twig', array('form' => $form->createView(), 'form_event' => $form_event->createView(), 'events' => $events, 'datatable' => $datatable));
    }

    public function uploadPhotosAction(Request $request, $eventSlug, $categorySlug = null) {
        $repository = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Event');
        $event = $repository->findOneBy(array('slug' => $eventSlug));
        $photo = new Photo();

	if ($categorySlug != null) {
	    $repository = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Category');
	    $category = $repository->findOneBy(array('slug' => $categorySlug));
	}

        $form = $this->createFormBuilder(array())
                        ->add('files', 'file', array('attr' => array('multiple' => true)))
                        ->getForm();

        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
var_dump($request->files->get('file'));
            $data = $form->getData();
var_dump($request->get('tags'));
            $photograph = $this->getUser()->getPhotograph();
            $em = $this->getDoctrine()->getManager();
	    $photos = array();

            foreach($request->files->get('file') as $file) {
                $photos[] = new Photo();
		$photo = end($photos);
//var_dump($file[0]);
var_dump($file);
                $photo->setFile(reset($file));
                $photo->setPhotograph($photograph);
                $photo->setValid(false);

		if ($categorySlug != null)
		    $photo->setCategory($category);

                $event->addPhoto($photo);
                if (!in_array($photograph, $photo->getEvent()->getPhotographs()->toArray()))
                    $photo->getEvent()->addPhotograph($photo->getPhotograph());
                $em->persist($event);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
	    $em->flush();

	    foreach($photos as $photo) {
		$aclProvider = $this->get('security.acl.provider');
                $objectIdentity = ObjectIdentity::fromDomainObject($photo);

                $acl = $aclProvider->createAcl($objectIdentity);

                $securityContext = $this->get('security.context');
                $user = $securityContext->getToken()->getUser();
                $securityIdentity = UserSecurityIdentity::fromAccount($user);

                $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                $aclProvider->updateAcl($acl);
	    }
        }

        $form=$form->createView();
        $form->children['files']->vars['full_name'] = 'form[files][]';

        return $this->render('LTPhotosBundle:Default:uploadPhotos.html.twig', array('form' => $form, 'event' => $event));
    }

    public function validImportAction(Request $request, $eventSlug) {
	$repository = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Event');
        $event = $repository->findOneBy(array('slug' => $eventSlug));

	$photograph = $this->getUser()->getPhotograph();

	$notif = new PhotosPostEvent($event, $photograph);

        $this
          ->get('event_dispatcher')
          ->dispatch(NotifMailEvents::onPhotosPost, $notif)
        ;
	return $this->render('LTPhotosBundle:Default:valideImport.html.twig');
    }

    public function helpAction() {
        return $this->render('LTPhotosBundle:Default:help.html.twig');
    }
    public function deleteAction(Photo $photo) {
	//pas besoin de l'implémenter pour le moment car non utilisé
    }

    public function censureAction(Photo $photo) {
	$em = $this->getDoctrine()->getManager();
	$photo->setCensured(true);
	$em->persist($photo);
	$em->flush();

	$scolarDate = $this->get('lt_photos.scolardate')->getScolarDate($photo->getEvent()->getDate());

	 return $this->redirect($this->generateUrl('lt_photos_base') . 'admin/' . $scolarDate['begin'] . '-' . $scolarDate['end'] . '/' . $photo->getEvent()->getSlug() . '/gallery/' . $photo->getPhotograph()->getId(), 301);

    }
    public function validAction(Photo $photo) {
	$em = $this->getDoctrine()->getManager();
	$photo->setCensured(false);
	$photo->setValid(true);
	$em->persist($photo);
	$em->flush();
	$scolarDate = $this->get('lt_photos.scolardate')->getScolarDate($photo->getEvent()->getDate());

	return $this->redirect($this->generateUrl('lt_photos_base') . 'admin/' . $scolarDate['begin'] . '-' . $scolarDate['end'] . '/' . $photo->getEvent()->getSlug() . '/gallery/' . $photo->getPhotograph()->getId(), 301);
    }
    public function createZipAction(Request $request, Event $event, Photograph $photograph = null, Category $category = null) {
	ob_start();
	$zip = new \ZipArchive;

	$name = '/var/www/photos/web/downloads/' . $event->getDate()->format('Y m d') . ' - ' . $event->getName();

	if ($category != null) {
	    $name .= ' - ';
	    $name .= $category->getName();
	}
	if ($photograph != null) {
	    $name .= ' - Photos de ';
	    $name .= $photograph->getNickname();
	}
	$name .= '.zip';

	$zip->open($name, \ZIPARCHIVE::CREATE | \ZipArchive::OVERWRITE);

	$repository = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Photo');
        $photos = $repository->getPhotosViewableWithPhotograph($photograph, $event, $category);

	foreach ($photos as $photo) {
	    $zip->addFile($photo->getAbsolutePath(), basename($photo->getOriginalName()));
	}
	$nameLicence="/var/www/photos/web/downloads/".strval(rand());
	$licence = fopen($nameLicence, "w");
	fputs($licence, $photograph->getLicence());
	fclose($licence);
	$zip->addFile($nameLicence, "LICENCE.txt");
	$zip->close();
	ob_end_clean();

        $zip=fopen($name, "r");
        $content=stream_get_contents($zip);

        return new Response($content, 200, array(
                'Content-Transfert-encoding: binary',
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="'.basename($name).'"',
                'Content-Length: '.filesize($name)
              ));
    }
}
