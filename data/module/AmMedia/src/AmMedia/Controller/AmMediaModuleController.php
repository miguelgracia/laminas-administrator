<?php
namespace AmMedia\Controller;

use Administrator\Controller\AuthController;
use Zend\Http\Headers;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AmMediaModuleController extends AuthController
{
    protected $config;

    /**
     * @return array|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $queryParams = $this->getRequest()->getQuery();

        if ($queryParams->modal == 'on') {
            $this->layout()->setTemplate('layout/filemanager-layout');
        }
    }

    public function connectorAction()
    {
        $fileManager = $this->serviceLocator->get('AmMedia\FileManager\FileManagerService');
        $fileManagerResponse = $fileManager->handleRequest();

        $controllerResponse = $this->getResponse();
        $controllerResponse->setContent($fileManagerResponse->getContent());

        $fileManagerHeaders = $fileManagerResponse->getHeaders()->toArray();

        $controllerHeaders = $controllerResponse->getHeaders();
        $controllerHeaders->clearHeaders()->addHeaders($fileManagerHeaders);

        return $controllerResponse;
    }
}