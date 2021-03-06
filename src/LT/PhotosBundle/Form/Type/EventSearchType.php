<?php

namespace LT\PhotosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventSearchType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('name', null, array('required' => false))
            ->add('dateFrom', 'datePicker', array('required' => false))
            ->add('dateTo', 'datePicker', array('required' => false))
            ->add('search', 'submit');
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    parent::setDefaultOptions($resolver);

    $resolver->setDefaults(array(
      'csrf_protection' => false,
      'data_class' => 'LT\PhotosBundle\Model\EventSearch'
    ));
  }

  public function getName() {
    return 'event_search_type';
  }
}
