<?php

namespace LT\PhotosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use LT\PhotosBundle\Entity\FormContact;
use LT\PhotosBundle\Entity\Event;
use LT\PhotosBundle\Entity\Photograph;
use LT\PhotosBundle\Entity\Category;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LTPhotosBundle:Default:index.html.twig');
    }

    public function recentAction() {
	$repository = $this
	  ->getDoctrine()
	  ->getManager()
	  ->getRepository('LTPhotosBundle:Event')
	;

	$repoPhoto = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('LTPhotosBundle:Photo')
        ;

	$listEvents=array();
	$listAllEvents = $repository->findBy(
	  array(), // Critere
	  array('date' => 'desc'),        // Tri
	  10,                              // Limite
	  0                               // Offset
	);

	foreach ($listAllEvents as $event) {
	  if (count($repoPhoto->getPhotosViewable($event)) != 0)
	    $listEvents[] = $event;
	}


	return $this->render('LTPhotosBundle:Default:recent.html.twig', array('listEvents' => $listEvents));
    }

    public function yearAction($year1, $year2) {
	$repository = $this
	  ->getDoctrine()
	  ->getManager()
	  ->getRepository('LTPhotosBundle:Event')
	;

	$repoPhoto = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('LTPhotosBundle:Photo')
        ;

	$breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("lt_photos_index"));
        $breadcrumbs->addItem("$year1-$year2", $this->get("router")->generate("lt_photos_year", array('year1' => $year1, 'year2' => $year2)));

	$listAllEvents = $repository->findByScolarYear($year1, $year2);
	$listEvents=array();

        foreach ($listAllEvents as $event) {
          if (count($repoPhoto->getPhotosViewable($event)) != 0)
            $listEvents[] = $event;
        }


	return $this->render('LTPhotosBundle:Default:year.html.twig', array('listEvents' => $listEvents));
    }

    public function eventAction() {
	$repository = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Event');

	$listAllEvents = $repository->findThisYear();
	$listEvents=array();

	$repoPhoto = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('LTPhotosBundle:Photo')
        ;

	foreach ($listAllEvents as $event) {
          if (count($repoPhoto->getPhotosViewable($event)) != 0)
            $listEvents[] = $event;
        }

	return $this->render('LTPhotosBundle:Default:recent.html.twig', array('listEvents' => $listEvents));
    }

    public function photographAction($year1, $year2, $slug) {
        $repository = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Event');
        $repoPhoto = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Photo');

        $event = $repository->findOneBy(array('slug' => $slug));

	if (count($event->getCategories()) == 0) {
	    return $this->redirect($this->generateUrl('lt_photos_gallery',
							array('year1' => $year1,
							      'year2' => $year2,
							      'slug' => $slug,
							      'photograph' => null)
						 )
			      );
	} else {
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem("Home", $this->get("router")->generate("lt_photos_index"));
	    $breadcrumbs->addItem("2014-2015", $this->get("router")->generate("lt_photos_year", array('year1' => $year1, 'year2' => $year2)));
	    $breadcrumbs->addItem($event->getName(), $this->get("router")->generate("lt_photos_photograph", array('year1' => $year1, 'year2' => $year2, 'slug' => $slug)));

	    $categories = array();
	    foreach ($event->getCategories() as $category) {
		if (count($repoPhoto->getPhotosViewable($event, $category)) != 0) {
		    $categories[] = $category;
		}
	    }

	    return $this->render('LTPhotosBundle:Default:choicePhotograph.html.twig', array('categories' => $categories));
	}
    }

    public function archivesAction() {
	$repository = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Event');
	$firstEvent = $repository->findBy(
	  array(),
	  array('date' => 'asc'),
	  1,
	  0
	);

	$lastEvent = $repository->findBy(
	  array(),
	  array('date' => 'desc'),
	  1,
	  0
	);

	$firstYear = $firstEvent[0]->getDate()->format('Y') + floor($firstEvent[0]->getDate()->format('m') / 9) - 1;
	$lastYear = $lastEvent[0]->getDate()->format('Y') + floor($lastEvent[0]->getDate()->format('m') /9) - 1;

	$j=0;
	for ($i = $firstYear ; $i <= $lastYear ; $i++) {
	    $years[$j] = strval($i)."-".strval($i+1);
	    $j++;
	}

	return $this->render('LTPhotosBundle:Default:archives.html.twig', array('years' => $years));
    }

    /**
     * @ParamConverter("category", options={"mapping": {"slug_cat": "slug"}})
     * @ParamConverter("event", options={"mapping": {"slug": "slug"}})
     */
    public function galleryAction($year1, $year2, Event $event, Category $category = null, Photograph $photograph = null) {
	$repository = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Photo');
	$photos = $repository->getPhotosViewable($event, $category);

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("lt_photos_index"));
        $breadcrumbs->addItem("$year1-$year2", $this->get("router")->generate("lt_photos_year", array('year1' => $year1, 'year2' => $year2)));
        $breadcrumbs->addItem($event->getDate()->format('Y m d') . ' - ' . $event->getName(), $this->get("router")->generate("lt_photos_photograph", array('year1' => $year1, 'year2' => $year2, 'slug' => $event->getSlug())));
	if ($category != null)
	    $breadcrumbs->addItem($category->getName());

	$photographs=array();
	foreach ($event->getPhotographs() as $photograph) {
	    if (count($repository->getPhotosViewableWithPhotograph($photograph, $event, $category)) != 0)
	        $photographs[]=$photograph;
	}

	return $this->render('LTPhotosBundle:Default:gallery.html.twig', array('event' => $event, 'photos' => $photos, 'photographs' => $photographs));
    }
}

