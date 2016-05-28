<?php

namespace AmbienteLogin\Service;


class LoginService {

    private  $login;
    private  $senha;

    public function logar($username, $password)
    {

        $auth  = new \Zend\Authentication\AuthenticationService();

        $sessionStorage = new \Zend\Authentication\Storage\Session('SessaoAmbienteLogin');

        $adapter = new AmbienteAdapter();

        $this->setLogin($username);
        $this->setSenha($password);

        $auth->setStorage($sessionStorage);

        $adapter->setUsername($username);
        $adapter->setPassword($password);

        $result = $auth->authenticate($adapter);

        if ($result->isValid()) {
            $this->escreveDadosNaSessao();
            return true;
        }

        if (!$result->isValid()) {
            return false;
        }

        return null;
    }

    private function escreveDadosNaSessao()
    {

        $sessionStorage =  new \Zend\Authentication\Storage\Session('SessaoAmbienteLogin');
        $sessaoLogin = $sessionStorage->read();

        $sessaoLogin['usuario_logado'] = true;

        $sessionStorage->write($sessaoLogin);
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param mixed $senha
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }
}