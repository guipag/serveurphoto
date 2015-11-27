<?php

namespace LT\PhotosBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class EventDatatable
 *
 * @package LT\PhotosBundle\Datatables
 */
class EventDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable()
    {
        /*$this->topActions->set(array(
            'start_html' => '<div class="row"><div class="col-sm-3">',
            'end_html' => '<hr></div></div>',
            'actions' => array(
                array(
                    'route' => $this->router->generate('post_new'),
                    'label' => $this->translator->trans('datatables.actions.new'),
                    'icon' => 'glyphicon glyphicon-plus',
                    //'role' => 'ROLE_USER',
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => $this->translator->trans('datatables.actions.new'),
                        'class' => 'btn btn-primary',
                        'role' => 'button'
                    ),
                )
            )
        ));*/

        $this->features->setFeatures(array(
            'auto_width' => true,
            'defer_render' => false,
            'info' => false,
            'jquery_ui' => false,
            'length_change' => false,
            'ordering' => true,
            'paging' => true,
            'processing' => false,
            'scroll_x' => false,
            'scroll_y' => '',
            'searching' => true,
            'server_side' => true,
            'state_save' => true,
            'delay' => 0
        ));

        $this->ajax->setOptions(array(
            'url' => $this->router->generate('event_results'),
            'type' => 'GET'
        ));

        $this->options->setOptions(array(
            'display_start' => 0,
            'defer_loading' => -1,
            'dom' => 'lfrtip',
            'length_menu' => array(10, 25, 50, 100),
            'order_classes' => true,
            'order' => array(array(2, 'desc')),
            'order_multi' => true,
            'page_length' => 10,
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'renderer' => 'bootstrap',
            'scroll_collapse' => false,
            'search_delay' => 0,
            'state_duration' => 7200,
            'stripe_classes' => array(),
            'responsive' => true,
            'class' => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering' => false,
            'individual_filtering_position' => 'foot',
            'use_integration_options' => true
        ));

        $this->columnBuilder
                ->add('id', 'column', array('title' => 'Id',))
                ->add('name', 'column', array('title' => 'Nom',))
                ->add('date', 'datetime', array('title' => 'Date début',))
                ->add('dateFin', 'datetime', array('title' => 'Date fin',))
                ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'LT\PhotosBundle\Entity\Event';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'event_datatable';
    }
}
