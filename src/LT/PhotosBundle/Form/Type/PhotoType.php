<?php

namespace LT\PhotosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhotoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	    ->add('file', 'file', array('attr' => array('multiple' => true)))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LT\PhotosBundle\Entity\Photo',
            'attr' => ['id' => 'photoform']
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lt_photosbundle_photo';
    }
}
