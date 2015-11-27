<?php

namespace LT\PhotosBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use LT\PhotosBundle\Entity\Photo;
use LT\PhotosBundle\Entity\Event;
use LT\PhotosBundle\Entity\Photograph;
use LT\PhotosBundle\Form\EventType;

/**
 * Event controller.
 *
 */
class EventController extends Controller
{

    /**
     * Lists all Event entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LTPhotosBundle:Event')->findAllDateDesc();

        return $this->render('LTPhotosBundle:Event:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Event entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Event();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

	    if ($this->get('security.context')->isGranted('ROLE_ADMIN'))
                return $this->redirect($this->generateUrl('event_show', array('id' => $entity->getId())));
	    else
		return $this->redirect($this->generateUrl('lt_photos_upload'));
        }

        return $this->render('LTPhotosBundle:Event:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Event entity.
     *
     * @param Event $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Event $entity)
    {
        $form = $this->createForm(new EventType(), $entity, array(
            'action' => $this->generateUrl('event_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Event entity.
     *
     */
    public function newAction()
    {
        $entity = new Event();
        $form   = $this->createCreateForm($entity);

        return $this->render('LTPhotosBundle:Event:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Event entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LTPhotosBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('LTPhotosBundle:Event:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Event entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LTPhotosBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $repository = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Photo');

	$nbrePhotos = array();
	foreach ($entity->getPhotographs() as $photograph) {
          $nbrPhoto = $repository->createQueryBuilder('a')
				    ->select('COUNT(a)')
				    ->where('a.photograph = :photograph')
				    ->andWhere('a.event = :event')
				    ->setParameter('photograph', $photograph)
				    ->setParameter('event', $entity)
				->getQuery()
				->getSingleScalarResult()
			;
	  $nbrePhotos[$photograph->getNickname()] = $nbrPhoto;
	}

	$nbrPhotosCensured = array();
        foreach ($entity->getPhotographs() as $photograph) {
          $nbrPhotoCensured = $repository->createQueryBuilder('a')
                                    ->select('COUNT(a)')
                                    ->where('a.photograph = :photograph')
                                    ->andWhere('a.event = :event')
				    ->andWhere('a.censured = :censured')
                                    ->setParameter('photograph', $photograph)
                                    ->setParameter('event', $entity)
				    ->setParameter('censured', true)
                                ->getQuery()
                                ->getSingleScalarResult()
                        ;
          $nbrPhotosCensured[$photograph->getNickname()] = $nbrPhotoCensured;
        }

	$nbrPhotosToValidate = array();
	foreach ($entity->getPhotographs() as $photograph) {
          $nbrPhotoToValidate = $repository->createQueryBuilder('a')
                                    ->select('COUNT(a)')
                                    ->where('a.photograph = :photograph')
                                    ->andWhere('a.event = :event')
                                    ->andWhere('a.valid = :valid')
                                    ->setParameter('photograph', $photograph)
                                    ->setParameter('event', $entity)
                                    ->setParameter('valid', false)
                                ->getQuery()
                                ->getSingleScalarResult()
                        ;
          $nbrPhotosToValidate[$photograph->getNickname()] = $nbrPhotoToValidate;
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('LTPhotosBundle:Event:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
	    'nbrPhotos'   => $nbrePhotos,
	    'nbrPhotosCensured' => $nbrPhotosCensured,
	    'nbrPhotosToValidate' => $nbrPhotosToValidate,
        ));
    }

    public function deplaceAction(Request $request) {
	return $this->redirect($this->generateUrl('event_index'));
    }

    /**
    * Creates a form to edit a Event entity.
    *
    * @param Event $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Event $entity)
    {
        $form = $this->createForm(new EventType(), $entity, array(
            'action' => $this->generateUrl('event_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Event entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LTPhotosBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('event_edit', array('id' => $id)));
        }

        return $this->render('LTPhotosBundle:Event:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Event entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LTPhotosBundle:Event')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Event entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('event'));
    }

    /**
     * Creates a form to delete a Event entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function censureAction(Request $request, Event $event, Photograph $photograph) {
	$em = $this->getDoctrine()->getManager();
	$repository = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Photo');

	$photos = $repository->createQueryBuilder('a')
                                    ->select('a')
                                    ->where('a.photograph = :photograph')
                                    ->andWhere('a.event = :event')
                                    ->andWhere('a.valid = :valid')
                                    ->setParameter('photograph', $photograph)
                                    ->setParameter('event', $event)
                                    ->setParameter('valid', true)
                                ->getQuery()
                                ->getResult()
                        ;

	foreach ($photos as $photo) {
	    $photo->setValid(false);
	    $em->persist($photo);
	}

	$em->flush();
	//rediriger vers la page d'édition de l'event
	return $this->redirect($this->generateUrl('event_edit', array('id' => $event->getId())));
    }

    public function validAction(Request $request, Event $event, Photograph $photograph) {
	$em = $this->getDoctrine()->getManager();
	$repository = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Photo');

        $photos = $repository->createQueryBuilder('a')
                                    ->select('a')
                                    ->where('a.photograph = :photograph')
                                    ->andWhere('a.event = :event')
                                    ->andWhere('a.valid = :valid')
                                    ->setParameter('photograph', $photograph)
                                    ->setParameter('event', $event)
                                    ->setParameter('valid', false)
                                ->getQuery()
                                ->getResult()
                        ;

        foreach ($photos as $photo) {
            $photo->setValid(true);
            $em->persist($photo);
        }

        $em->flush();
        //rediriger vers la page d'édition de l'event
        return $this->redirect($this->generateUrl('event_edit', array('id' => $event->getId())));
    }

    public function resultsAction()
    {
        $datatable = $this->get('app.datatable.event');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
	$resp = $query->getResponse();

	$logger = $this->get('logger');
	$logger->info($resp);

        return $resp;
    }

    public function bulkDeleteAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $choices = $request->request->get("data");

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LTPhotosBundle:Event");

            foreach ($choices as $choice) {
                $entity = $repository->find($choice["value"]);
                $em->remove($entity);
            }
            $em->flush();

           return new Response("Success", 200);
        }
        return new Response("Bad Request", 400);
    }

    public function bulkInvisibleAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $choices = $request->request->get("data");

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LTPhotosBundle:Event");

            foreach ($choices as $choice) {
                $entity = $repository->find($choice["value"]);
                $entity->setVisible(false);
                $em->persist($entity);
            }

            $em->flush();

            return new Response("Success", 200);
        }

        return new Response("Bad Request", 400);
    }
}
