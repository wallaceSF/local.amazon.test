<?php
return array(
    'router' => array(
        'routes' => array(
            'ambiente-login' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/authentication/[:action]',
                    'defaults' => array(
                        'controller' => 'AmbienteLogin\Controller\Login',
                        'action' => 'login'
                    ),
                ),
            ),
        ),
    ),
    'controllers'  => array(
        'invokables' => array(
            'AmbienteLogin\Controller\Login' => 'AmbienteLogin\Controller\LoginController'
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'AmbienteLogin\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService',
            'AmbienteLogin\Service\LoginService'                 => 'AmbienteLogin\Service\LoginService',
        ),
        'factories' => array(
            'AmbienteLogin\Storage\Session'   => function (
            ){
                return new Zend\Authentication\Storage\Session('SessaoAmbienteLogin');
            },
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);