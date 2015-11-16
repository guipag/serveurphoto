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

class ImportCommand extends ContainerAwareCommand {
    protected function configure() {
        $this
            ->setName('photos:import')
            ->setDescription('Script d\'import des photos de l\'ancien vers le nouveau serveur photo')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Quel dossier voulez-vous importer ?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $path = $input->getArgument('path');
	$em = $this->getContainer()->get('doctrine')->getManager();

	$finder = new Finder();
	$finder->depth('== 0');
	$finder->directories()->in($path);

	$output->writeln("Etape 1 : importation des photos");

	$progress = $this->getHelper('progress');
	$progress->start($output, count($finder));

	foreach ($finder as $file) {
//$output->writeln($file->getRelativePathName());
	    $textdate = preg_replace('#^(\d{4}) (\d{2}) (\d{2})(.+)$#isU', '$1 $2 $3', $file->getRelativePathName());
	    $date = \DateTime::createFromFormat('Y m d', $textdate);

	    $name = preg_replace('#^\d{4} \d{2} \d{2} - (.+)$#isU', '$1', $file->getRelativePathName());

	    $repoEvent = $em->getRepository('LTPhotosBundle:Event');
	    $event = $repoEvent->findOneBy(array('name' => $name, 'date' => $date));

	    if ($event == null) {
	        $event = new Event();
	        $event->setDate($date);
	        $event->setName($name);
	    }

	    $finderCat = new Finder();
	    $finderCat->depth('==0');
	    $finderCat->directories()->in($file->getPathName());

	    foreach ($finderCat as $cat) {
		if (!preg_match('#^(Photos?)|(Vidé|eos?)#isU', $cat->getFileName())) {
		    //ajouter la catégorie puis traiter les photos dans la catégorie
		    $category = new Category();
		    $category->setName($cat->getFileName());

			//il faut ajouter la catégorie à la photo puis ajouter la photo à l'event
			// /!\ l'event n'a donc pas le lien avec la catégorie ! J'ai l'impression qu'il y a une couille dans le pâté
		    $event->addCategory($category);

		    var_dump($cat);
		} else {
		    //c'est un dossier de photo ou de vidéo
		    if (preg_match('#^(Photos?)#isU', $cat->getFileName())) {
			$namePhotograph = trim(preg_replace('#^Photos? d. ?(.+)#isU', '$1', $cat->getFileName()));

			$repoPhotograph = $em->getRepository('LTPhotosBundle:Photograph');
			$photograph = $repoPhotograph->findOneByName($namePhotograph);

			if ($photograph == null) {
			    $photograph = new Photograph();
			    $photograph->setName($namePhotograph);
			    $photograph->setFirstname($namePhotograph);
			    $photograph->setNickname($namePhotograph);
			}
			$event->addPhotograph($photograph);
			$em->persist($event);

			$finderPhotos = new Finder();
			$finderPhotos->depth('==0');
			$finderPhotos->files()->in($cat->getPathName());

			foreach ($finderPhotos as $filePhoto) {
			    $file = new UploadedFile($filePhoto, $filePhoto->getFileName(), null, null, null, true);
			    $photo = new Photo();
			    $photo->setFile($file);
			    $photo->setPhotograph($photograph);
			    $photo->setEvent($event);
			    $photo->setValid(true);
			    $photo->setCensured(false);
			    $em->persist($photo);

			    //création des miniatures
			    /*$this->container
            			 ->get('liip_imagine.controller')
                                 ->filterAction(
                                        $this->request,         // http request
                                        'uploads/foo.jpg',      // original image you want to apply a filter to
                                        'my_thumb'              // filter defined in config.yml
                                    );*/
			}
		    }
		}
	    }

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
