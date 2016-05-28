<?php

namespace AmbienteLogin\Controller;

use AmbienteLogin\Service\LoginService;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;

class LoginController extends AbstractActionController
{

    public function indexAction(){
        
    }


    public function loginAction()
    {

        $username = $this->params()->fromPost('username');
        $password = $this->params()->fromPost('password');

        $loginService = new LoginService();
        $return       = $loginService->logar($username, $password);

        if($return){
            return $this->redirect()->toRoute('cms');
        }

        return $this->redirect()->toRoute('ambiente-login', [
            'controller' => 'login',
            'action'     => 'index'
        ]);

    }

    public function logoutAction()
    {
        $auth           = new AuthenticationService;
        $sessionStorage = new Session('SessaoAmbienteLogin');

        $auth->setStorage($sessionStorage);
        $auth->clearIdentity();

        $session_user = new Container();
        $session_user->getManager()->getStorage()->clear();

        return $this->redirect()->toRoute('cms');
    }

}

