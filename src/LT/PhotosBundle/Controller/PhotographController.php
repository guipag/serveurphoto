<?php

namespace LT\PhotosBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

use LT\PhotosBundle\Entity\Photograph;
use LT\PhotosBundle\Form\PhotographType;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

/**
 * Photograph controller.
 *
 */
class PhotographController extends Controller
{

    /**
     * Lists all Photograph entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LTPhotosBundle:Photograph')->findAll();

        return $this->render('LTPhotosBundle:Photograph:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Photograph entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Photograph();

	$formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

	$user = $userManager->createUser();
        $user->setEnabled(true);
	$entity->setUser($user);

	$event = new GetResponseUserEvent($entity->getUser(), $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

	if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
	    $regex = "#^[a-zA-Z0-9_.+-]+@(ens-cachan\.fr)|(crans\.org)$#";
            if (!preg_match( $regex, $user->getEmail())) {
                $this->get('session')->getFlashBag()->add('warning', 'Votre email n\'est pas du Crans ou de l\'ENS');

	        return $this->render('LTPhotosBundle:Photograph:new.html.twig', array(
                    'entity' => $entity,
                    'form'   => $form->createView(),
                  ));
            }

	    $entity->getUser()->addRole('ROLE_PHOTOGRAPH');
	    $entity->getUser()->setPhotograph($entity);

	    $formUser = $formFactory->createForm();
            $formUser->setData($entity->getUser());

	    $event = new FormEvent($formUser, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($entity->getUser());

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

	    return $response;
        }

        return $this->render('LTPhotosBundle:Photograph:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Photograph entity.
     *
     * @param Photograph $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Photograph $entity)
    {
        $form = $this->createForm(new PhotographType(), $entity, array(
            'action' => $this->generateUrl('photograph_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Photograph entity.
     *
     */
    public function newAction()
    {
        $entity = new Photograph();
        $form   = $this->createCreateForm($entity);

        return $this->render('LTPhotosBundle:Photograph:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Photograph entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LTPhotosBundle:Photograph')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photograph entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('LTPhotosBundle:Photograph:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Photograph entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LTPhotosBundle:Photograph')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photograph entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

	foreach ($entity->getEvents() as $event) {
          $nbrPhotoCensured = $em->getRepository('LTPhotosBundle:Photo')->createQueryBuilder('a')
                                    ->select('COUNT(a)')
                                    ->where('a.photograph = :photograph')
                                    ->andWhere('a.event = :event')
                                    ->andWhere('a.censured = :censured')
                                    ->setParameter('photograph', $entity)
                                    ->setParameter('event', $event)
                                    ->setParameter('censured', true)
                                ->getQuery()
                                ->getSingleScalarResult()
                        ;
          $nbrPhotosCensured[$event->getId()] = $nbrPhotoCensured;
        }

        return $this->render('LTPhotosBundle:Photograph:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
	    'nbrPhotosCensured' => $nbrPhotosCensured
        ));
    }

    /**
    * Creates a form to edit a Photograph entity.
    *
    * @param Photograph $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Photograph $entity)
    {
        $form = $this->createForm(new PhotographType(), $entity, array(
            'action' => $this->generateUrl('photograph_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Photograph entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LTPhotosBundle:Photograph')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photograph entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('photograph_edit', array('id' => $id)));
        }

        return $this->render('LTPhotosBundle:Photograph:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Photograph entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LTPhotosBundle:Photograph')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Photograph entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('photograph'));
    }

    /**
     * Creates a form to delete a Photograph entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('photograph_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
