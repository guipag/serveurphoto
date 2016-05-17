<?php

namespace LT\PhotosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use LT\UserBundle\Form\Type\RegistrationFormType;

class PhotographType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('firstname')
            ->add('nickname')
            ->add('description')
	    ->add('licence')
	    ->add('user', new RegistrationFormType())
        ;
	return $builder;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LT\PhotosBundle\Entity\Photograph'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lt_photosbundle_photograph';
    }
}
