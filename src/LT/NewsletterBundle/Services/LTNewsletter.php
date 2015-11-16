<?php

namespace LT\NewsletterBundle\Newsletter;

class LTNewsletter {
  private $mailer;

  public function __construct(\Swift_mailer $mailer) {
    $this->mailer = $mailer;
  }

  public function send() {
    $message = \Swift_Message::newInstance()
      ->setSubject('Hello Email')
      ->setFrom('send@example.com')
      ->setTo('recipient@example.com')
      ->setBody($this->renderView('LTNewsletterBundle:Mail:newsletter.txt.twig', array('name' => $name)))
    ;

    $mailer->send($message);

  }
}
