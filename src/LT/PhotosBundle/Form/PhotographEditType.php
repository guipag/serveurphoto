<?php

namespace LT\PhotosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use LT\UserBundle\Form\Type\RegistrationFormType;

class PhotographEditType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('user')
        ;
	return $builder;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lt_photosbundle_photograph_edit';
    }

    public function getParent() {
	return new PhotographType();
    }
}
