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
        $this->features->setFeatures(array(
            'auto_width' => true,
            'defer_render' => false,
            'info' => false,
            'jquery_ui' => false,
            'length_change' => false,
            'ordering' => true,
            'paging' => true,
            'processing' => true,
            'scroll_x' => false,
            'scroll_y' => '',
            'searching' => true,
            'server_side' => true,
            'state_save' => false,
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
            'order' => array(array(0, 'asc')),
            'order_multi' => true,
            'page_length' => 10,
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'renderer' => '',
            'scroll_collapse' => false,
            'search_delay' => 0,
            'state_duration' => 7200,
            'stripe_classes' => array(),
            'responsive' => true,
            'class' => Style::BASE_STYLE,
            'individual_filtering' => false,
            'individual_filtering_position' => 'foot',
            'use_integration_options' => false
        ));

        $this->columnBuilder
                ->add('id', 'column', array('title' => 'Id',))
                ->add('name', 'column', array('title' => 'Nom',))
                ->add('date', 'column', array('title' => 'Date début',))
                ->add('dateFin', 'column', array('title' => 'Date fin',))
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
