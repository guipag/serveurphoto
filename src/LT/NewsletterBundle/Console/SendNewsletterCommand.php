<?php

namespace LT\NewsletterBundle\Console;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendNewsletterCommand extends ContainerAwareCommand {
  protected function configure()
  {
    $this
      ->setName('newsletter:send')
      ->setDescription('Envoyer les  informations de mise à jour du site internet')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    //appeler le service d'envoi des newsletters pour envoyer la newsletter
    $newsletterManager = $this->getContainer()->get('lt_newsletter.manager');
    $newsletterManager->send();
    $output->writeln("Newsletter envoyée");
  }
}
