<?php

namespace LT\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder->add('followed', 'checkbox', array('label' => 'Recevoir les notifications de mise en ligne par mail', 'required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
	$resolver->setDefaults(array(
	    'data_class' => 'LT\UserBundle\Entity\User'
	));
    }

    public function getParent() {
	return 'fos_user_registration';
    }

    public function getName() {
	return 'lt_user_registration';
    }
}

