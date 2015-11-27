<?php

namespace LT\PhotosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('date', 'datePicker', array())
	    ->add('dateFin', 'datePicker', array())
	   /* ->add('categories', 'collection', array(
					'type' => new CategoryType(),
					'allow_add'    => true,
        				'allow_delete' => true,
					'by_reference' => false))*/
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LT\PhotosBundle\Entity\Event'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lt_photosbundle_event';
    }
}
