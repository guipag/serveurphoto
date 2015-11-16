<?php

namespace LT\PhotosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller {
    public function eventSearchAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $motcle = '';
            $motcle = $request->request->get('motcle');
            $em = $this->container->get('doctrine')->getEntityManager();

            if ($motcle != '') {
                $qb = $em->createQueryBuilder();

                $qb->select('a')
                   ->from('LTPhotosBundle:Event', 'a')
                   ->where('a.name LIKE :motcle OR a.slug LIKE :motcle')
                   ->orderBy('a.date', 'DESC')
                   ->setParameter(':motcle', '%'.$motcle.'%');

                $events = $qb->getQuery()->getResult();
            }
            else {
                $events = $em->getRepository('LTPhotosBundle:Event')->findAll();
            }

            return $this->container->get('templating')->renderResponse('LTPhotosBundle:Default:listEvent.html.twig', array('events' => $events));
        }
    }

    public function eventAdminSearchAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $motcle = '';
            $motcle = $request->request->get('motcle');
            $em = $this->container->get('doctrine')->getEntityManager();

            if ($motcle != '') {
                $qb = $em->createQueryBuilder();

                $qb->select('a')
                   ->from('LTPhotosBundle:Event', 'a')
                   ->where('a.name LIKE :motcle OR a.slug LIKE :motcle')
                   ->orderBy('a.date', 'DESC')
                   ->setParameter(':motcle', '%'.$motcle.'%');

                $events = $qb->getQuery()->getResult();
            }
            else {
                $events = $em->getRepository('LTPhotosBundle:Event')->findAll();
            }

            return $this->container->get('templating')->renderResponse('LTPhotosBundle:Event:listEvent.html.twig', array('entities' => $events));
        }
    }
}
