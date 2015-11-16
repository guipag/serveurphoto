<?php

namespace LT\PhotosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EventSearchForm extends AbstractType {
    public function buildForm(FormBuilder $builder, array $options) {
	$builder->add('motcle', 'text', array('label' => 'Mot-clé'));
    }

    public function getName() {
	return 'eventsearch';
    }
}
