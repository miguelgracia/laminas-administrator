<?php
namespace AmMedia\Controller;

use Administrator\Controller\AuthController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AmMediaModuleController extends AuthController
{
    /**
     * @return array|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $view          = new ViewModel();
        $view->id      = $this->getEvent()->getRouteMatch()->getParam('id', 0);
        $view->model   = $this->getEvent()->getRouteMatch()->getParam('model', 'model');
        $this->Config  = $this->serviceLocator->get('Config');
        $view->DirJs   = $this->Config['AmMedia']['DirJs'];

        return $view;
    }

    /**
     * @return array
     */
    public function loadAction()
    {
        $view        = new ViewModel();
        $view->id    = $this->getEvent()->getRouteMatch()->getParam('id', 0);
        $view->model = $this->getEvent()->getRouteMatch()->getParam('model', 'model');

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('test' => 'test'));

        return $view->setTerminal(true);
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
}