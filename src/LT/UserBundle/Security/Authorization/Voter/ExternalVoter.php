<?php

namespace LT\UserBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentification\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ExternalVoter implements VoterInterface {
  private $roleVoter;

  public function __construct($roleVoter) {
    $this->roleVoter = $roleVoter;
  }
}
