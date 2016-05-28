<?php

namespace Cms\Controller;

use Cms\Service\CmsService;
use ImovelBase\Service\ImovelService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CmsController extends AbstractActionController
{

    public function indexAction()
    {
        $viewModel     = new ViewModel();
        $imovelService = new ImovelService();

        $imoveis = $imovelService->getAllImoveis($this->serviceLocator);

        $viewModel->setVariable('imoveis', $imoveis);

        return $viewModel;

    }

    public function viewCadastrarAction()
    {
        
    }

    public function viewEditarAction()
    {
        $id   = $this->params()->fromRoute('id');

        $viewModel     = new ViewModel();

        //Todo::Descobrir como injetar com o novo ServiceLocator
        $imovelService = new ImovelService();

        $imovel = $imovelService->getImovelById($this->serviceLocator, $id);

        $viewModel->setVariable('imovel', $imovel);
        $viewModel->setVariable('id', $id);

        return $viewModel;

    }



    public function createAction()
    {

        $data       = $this->params()->fromPost();
        $file       = $this->params()->fromFiles();
        
        $serviceCms = new CmsService();
        $return     = $serviceCms->cadastrarImovel($this->serviceLocator, $data, $file);

        if ($return) {
            //Todo::Criar o flash messenger success
            return $this->redirect()->toRoute('cms', [
                'controller' => 'cms',
                'action'     => 'index'
            ]);
        }

        //Todo::Criar o flash messenger fail
        return $this->redirect()->toRoute('cms', [
            'controller' => 'cms',
            'action'     => 'index'
        ]);

    }


    public function putAction()
    {
        $id   = $this->params()->fromRoute('id');
        $data = $this->params()->fromPost();
        $file = $this->params()->fromFiles();

        $serviceCms = new CmsService();
        $return     = $serviceCms->editarImovel($this->serviceLocator, $id, $data, $file);

        if ($return) {
            //Todo::Criar o flash messenger success
            return $this->redirect()->toRoute('cms', [
                'controller' => 'cms',
                'action'     => 'index'
            ]);
        }

        //Todo::Criar o flash messenger fail
        return $this->redirect()->toRoute('cms', [
            'controller' => 'cms',
            'action'     => 'index'
        ]);
    }


    public function deleteAction()
    {

        $id   = $this->params()->fromRoute('id');

        $serviceCms = new CmsService();
        $serviceCms->excluirImovel($this->serviceLocator, $id);


    }


    




}

