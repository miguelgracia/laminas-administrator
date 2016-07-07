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

    /**
     * @return mixed
     */
    public function uploadAction()
    {
        if ($this->getRequest()->isPost()) {

            $post = $this->getRequest()->getPost();
            $data = array_merge_recursive(
                $post->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

            $id    = $post->module_id;
            $model = $post->module_target;

            $plUploadService = $this->serviceLocator->get('plupload_service');

            $plUploadService->upload($id,$data,$model);
        }

        return new JsonModel(array());
    }

    /**
     * @return mixed
     */
    public function removeAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id', 0);
        $plUploadService = $this->serviceLocator->get('plupload_service');
        $plUploadService->PluploadRemove($id);

        //ReloadLoad List
        $view        = new ViewModel();
        $view->id    = $this->getEvent()->getRouteMatch()->getParam('id_parent', 0);
        $view->model = $this->getEvent()->getRouteMatch()->getParam('model', 'model');
        return $view->setTerminal(true);
    }

    protected function accessErrorHandler($errorKey)
    {
        $request = $this->serviceLocator->get('Request');

        if ($request->isXmlHttpRequest()) {
            $fileManager = $this->serviceLocator->get('AmMedia\FileManager\FileManagerService');
            return $fileManager->error($this->errorMessages[$errorKey]);
        }

        $this->flashMessenger()->addMessage($this->errorMessages[$errorKey]);
        return $this->goToSection('login');
    }
}