<?php

namespace AmBlog\Controller;

use Administrator\Controller\AuthController;
use AmBlog\Form\BlogFieldset;
use AmBlog\Form\BlogForm;
use Zend\View\Model\ViewModel;

class AmBlogModuleController extends AuthController
{

    protected $blogTable = null;

    public function setControllerVars()
    {
        $this->blogTable = $this->sm->get('AmBlog\Model\BlogTable');
        $this->formService  = $this->sm->get('Administrator\Service\AdministratorFormService')->setTable($this->blogTable);
    }

    public function addAction()
    {
        $row = $this->blogTable->getEntityModel();

        $fieldset = new BlogFieldset($this->serviceLocator,$row,$this->blogTable);

        $this->formService
            ->setForm()
            ->addFieldset($fieldset)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $row->exchangeArray($form->getData());

                $insertId = 0; //$this->blogTable->saveEntradaMenu($row);

                return $this->goToSection('menu', array(
                    'action'  => 'edit',
                    'id'      => $insertId
                ));
            }
        }

        return new ViewModel(compact( 'form' ));
    }

    public function editAction()
    {
        return new ViewModel();
    }


}

