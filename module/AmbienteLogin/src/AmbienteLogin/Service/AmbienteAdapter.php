<?php

namespace AmbienteLogin\Service;

use Zend\Authentication\Result;

class AmbienteAdapter extends AbstractAdapter {

    public function authenticate()
    {

        $data["data"]["username"] = $this->getUsername();
        $data["data"]["password"] = $this->getPassword();

        if ($data["data"]["username"] == 'admin' && $data["data"]["password"] == '123456') {
            return new Result(Result::SUCCESS, ['ok'], ['ok1']);
        }

        return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ['fail']);


    }
}