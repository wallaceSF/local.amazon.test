<?php

namespace AmbienteLogin;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function init(ModuleManager $moduleManager)
    {

        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();


        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController',
                              MvcEvent::EVENT_DISPATCH,
                              array($this, 'verificaAutenticacao'),
                              100);
    }

    public function verificaAutenticacao($e)
    {

        $serviceManager = $e->getApplication()->getServiceManager();

        /** @var \Zend\Authentication\Storage\StorageInterface $sessionStorage */
        $sessionStorage = $serviceManager->get('AmbienteLogin\Storage\Session');
        $sessaoLogin    = $sessionStorage->read();

        $controller = $e->getTarget();
        $rotaAtual  = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();

        $rotasQuePrecisamDeAutenticacao = ['cms'];
        $checagemRotas = in_array($rotaAtual,$rotasQuePrecisamDeAutenticacao);

        if (!$sessaoLogin && $checagemRotas) {
            return $controller->redirect()->toRoute('ambiente-login', [
                'controller' => 'login',
                'action' => 'index'
            ]);
        }

        if ($sessaoLogin){
            $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
            $viewModel->sessaoUsuario = $sessaoLogin;
        }

    }

}

