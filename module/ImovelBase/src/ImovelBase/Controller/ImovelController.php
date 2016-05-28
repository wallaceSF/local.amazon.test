<?php

namespace ImovelBase\Controller;

use ImovelBase\Service\ImovelService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ImovelController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function imovelAction()
    {
        $viewModel     = new ViewModel();
        $imovelService = new ImovelService();

        $imovelNome = $this->params()->fromQuery('imovel-nome');
        
        $imovel = $imovelService->getImovelByName($this->serviceLocator, $imovelNome);

        $viewModel->setVariable('imovel', $imovel);

        $viewModel->setTerminal(true);
        
        return $viewModel;

    }

    public function imoveisNasProximidadesAction()
    {
        $viewModel     = new ViewModel();
        $imovelService = new ImovelService();

        $imovelLatitude  = $this->params()->fromQuery('latitude');
        $imovelLongitude = $this->params()->fromQuery('longitude');

        $imoveis = $imovelService->getImoveisNasProximidades($this->serviceLocator, $imovelLatitude, $imovelLongitude);


        $viewModel->setVariable('imoveis', $imoveis);

        $viewModel->setTerminal(true);

        return $viewModel;

    }

    public function listarTodosImoveisAction()
    {
        $viewModel     = new ViewModel();
        $imovelService = new ImovelService();

        $imoveis = $imovelService->getAllImoveis($this->serviceLocator);

        $viewModel->setVariable('imoveis', $imoveis);

        return $viewModel;
    }


}
