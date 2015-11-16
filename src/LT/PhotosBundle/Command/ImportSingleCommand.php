<?php

namespace LT\PhotosBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use LT\PhotosBundle\Entity\Event;
use LT\PhotosBundle\Entity\Photograph;
use LT\PhotosBundle\Entity\Photo;
use LT\PhotosBundle\Entity\Category;

class ImportSingleCommand extends ContainerAwareCommand {
    protected function configure() {
        $this
            ->setName('photos:import:single')
            ->setDescription('Script d\'import des photos')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Quel dossier voulez-vous importer ?')
	    ->addArgument(
		'photograph',
		InputArgument::REQUIRED,
		'L\'ID du photographe')
            ->addArgument(
                'event',
                InputArgument::REQUIRED,
                'L\'ID de l\'évènement')
            ->addArgument(
                'category',
                InputArgument::OPTIONAL,
                'La catégorie éventuelle')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $path 	      = $input->getArgument('path');
        $photographId = $input->getArgument('photograph');
        $eventId      = $input->getArgument('event');
        $categoryId   = $input->getArgument('category');

	$em = $this->getContainer()->get('doctrine')->getManager();

	$finder = new Finder();
	$finder->depth('== 0');
	$finder->files()->in($path);

	$output->writeln("Etape 1 : importation des photos");

	$progress = $this->getHelper('progress');
	$progress->start($output, count($finder));

	$repoEvent = $em->getRepository('LTPhotosBundle:Event');
        $event = $repoEvent->findById($eventId)[0];

        $repoPhotograph = $em->getRepository('LTPhotosBundle:Photograph');
        $photograph = $repoPhotograph->findById($photographId)[0];

	$category = null;
	if ($categoryId != null) {
	    $repoCategory = $em->getRepository('LTPhotosBundle:Category');
	    $category = $repoCategory->findById($categoryId)[0];
	}

	if (!$event->getPhotographs()->contains($photograph))
	    $event->addPhotograph($photograph);

        $em->persist($event);

	foreach ($finder as $file) {
	    $file = new UploadedFile($file, $file->getFileName(), null, null, null, true);
	    $photo = new Photo();
	    $photo->setFile($file);
	    $photo->setPhotograph($photograph);
	    $photo->setEvent($event);
	    $photo->setValid(false);
	    $photo->setCensured(false);

	    if ($category != null) {
		$photo->setCategory($category);
	    }

	    $em->persist($photo);

	    $progress->advance();
	}
        $em->flush();
	$progress->finish();

	$output->writeln("Etape 2 : génération des miniatures");

	$findMin = new Finder();
	$findMin->files()->name('*.jpg')->name('*.JPG')->name('*.jpeg')->name('*.JPEG')->in($path);

	$progress = $this->getHelper('progress');
        $progress->start($output, count($findMin));

	foreach ($findMin as $photo) {
	    //$this->getContainer()
    	//	->get('liip_imagine.controller')
        //	->filterAction($this->getContainer()->get('request'), $photo->getRealpath(), 'my_thumb');

	    $progress->advance();
        }
	$progress->finish();
    }
}
