<?php
return array(
    'router' => array(
        'routes' => array(
            'cms' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/cms[/][:action][/:id]',
                    'defaults' => array(
                        'controller' => 'Cms\Controller\Cms',
                        'action' => 'index'
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Cms\Controller\Cms' => 'Cms\Controller\CmsController'
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'Cms\Service\CmsService' => 'Cms\Service\CmsService',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);