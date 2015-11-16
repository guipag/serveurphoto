<?php

namespace LT\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	    ->add('username')
	    ->add('email')
	    ->add('password', 'repeated', array(
				 'type' => 'password',
				 'invalid_message' => 'Les mots de passe doivent correspondre',
    				 'options' => array('required' => true),
    				 'first_options'  => array('label' => 'Mot de passe'),
    				 'second_options' => array('label' => 'Mot de passe (validation)')))
	    ->add('roles', 'choice', array(
                                 'choices'   => array(
                                    'ROLE_ADMIN'       => 'ROLE_ADMIN',
                                    'ROLE_USER'        => 'ROLE_USER',
                                    'ROLE_PHOTOGRAPH'  => 'ROLE_PHOTOGRAPH',
				    'ROLE_MODERATEUR'  => 'ROLE_MODERATEUR',
				    'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                                ),
                                'mapped'    => true,
				'expanded'  => true,
                                'multiple'  => true));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LT\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lt_userbundle_user';
    }
}
