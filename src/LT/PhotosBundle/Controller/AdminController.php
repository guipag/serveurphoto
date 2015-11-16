<?php

namespace LT\PhotosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LT\PhotosBundle\Entity\Category;
use LT\PhotosBundle\Entity\Event;
use LT\PhotosBundle\Entity\Photograph;

class AdminController extends Controller {
    public function indexAction() {
	return $this->render('LTPhotosBundle:Admin:index.html.twig');
    }
    public function galleryAction($year1, $year2, Event $event, Category $category = null, Photograph $photograph = null) {
        $repository = $this->getDoctrine()->getManager()->getRepository('LTPhotosBundle:Photo');
        $photos = $repository->getAllPhotos($event, $category);

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("lt_photos_index"));
        $breadcrumbs->addItem("$year1-$year2", $this->get("router")->generate("lt_photos_year", array('year1' => $year1, 'year2' => $year2)));
        $breadcrumbs->addItem($event->getName(), $this->get("router")->generate("lt_photos_photograph", array('year1' => $year1, 'year2' => $year2, 'slug' => $event->getSlug())));
        if ($category != null)
            $breadcrumbs->addItem($category->getName());

        return $this->render('LTPhotosBundle:Admin:gallery.html.twig', array('photos' => $photos, 'photographs' => $event->getPhotographs()));
    }
}
