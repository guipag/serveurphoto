<?php

namespace LT\UserBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ExternalVoter implements VoterInterface {
  private $roleVoter;

  public function __construct($roleVoter) {
    $this->roleVoter = $roleVoter;
  }
}
