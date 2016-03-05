<?php

namespace LT\UserBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class UserDatatable
 *
 * @package LT\UserBundle\Datatables
 */
class UserDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = Array())
    {
        $this->features->set(array(
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

        $this->ajax->set(array(
            'url' => $this->router->generate('user_results'),
            'type' => 'GET'
        ));

        $this->options->set(array(
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
            'class' => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering' => false,
            'individual_filtering_position' => 'foot',
            'use_integration_options' => true
        ));

        $this->columnBuilder
                ->add('id', 'column', array('title' => 'Id',))
                ->add('username', 'column', array('title' => 'Nom d\'utilisateur',))
                ->add('email', 'column', array('title' => 'Email',))
                ->add('enabled', 'boolean', array('title' => 'Activé',
						'true_icon' => 'glyphicon glyphicon-ok',
        					'false_icon' => 'glyphicon glyphicon-remove',
        					'true_label' => 'Oui',
        					'false_label' => 'Non'))
                ->add(null, 'action', array(
                        'title' => 'Actions',
                        'start_html' => '<div class="btn-group" role="group">',
                        'end_html' => '</div>',
                        'actions' => array(
                            array(
                                'route' => 'user_show',
                                'route_parameters' => array(
                                    'id' => 'id'
                                    ),
                                'icon' => 'glyphicon glyphicon-eye-open',
                                'attributes' => array(
                                    'rel' => 'tooltip',
                                    'title' => 'Voir',
                                    'class' => 'btn btn-primary btn-xs',
                                    'role' => 'button'
                                    )
                                ),
                            array(
                                'route' => 'user_edit',
                                'route_parameters' => array(
                                    'id' => 'id'
                                    ),
                                'icon' => 'glyphicon glyphicon-edit',
                                'attributes' => array(
                                    'rel' => 'tooltip',
                                    'title' => 'Editer',
                                    'class' => 'btn btn-primary btn-xs',
                                    'role' => 'button'
                                    )
                                )
			    )
			)
		     )
                ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'LT\UserBundle\Entity\User';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_datatable';
    }
}
