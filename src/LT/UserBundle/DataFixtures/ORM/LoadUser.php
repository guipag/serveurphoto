<?php

namespace LT\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LT\UserBundle\Entity\User;

class LoadUser implements FixtureInterface {
    public function load(ObjectManager $manager) {
	$listNames = array('Lancelot', 'Papa ours', 'VCR');

	foreach ($listNames as $name) {
	    $user = new User;
	    $user->setUsername($name);
	    $user->setPassword($name);

	    $user->setSalt('');

	    $user->setRoles(array('ROLE_USER', 'ROLE_ADMIN'));

	    $manager->persist($user);
	}

	$manager->flush();
    }
}
