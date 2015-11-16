<?php

namespace LT\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller {
    public function userSearchAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $motcle = '';
            $motcle = $request->request->get('motcle');
            $em = $this->container->get('doctrine')->getEntityManager();

            if ($motcle != '') {
                $qb = $em->createQueryBuilder();

                $qb->select('a')
                   ->from('LTUserBundle:User', 'a')
                   ->where('a.username LIKE :motcle OR a.email LIKE :motcle')
                   ->orderBy('a.username')
                   ->setParameter(':motcle', '%'.$motcle.'%');

                $entities = $qb->getQuery()->getResult();
            }
            else {
                $entities = $em->getRepository('LTUserBundle:User')->findAll();
            }

            return $this->container->get('templating')->renderResponse('LTUserBundle:User:listUser.html.twig', array('entities' => $entities));
        }
    }
}
