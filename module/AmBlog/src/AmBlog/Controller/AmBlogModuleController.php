<?php

namespace AmBlog\Controller;

use Administrator\Controller\AuthController;
use AmBlog\Form\BlogFieldset;
use AmBlog\Form\BlogForm;
use AmBlog\Form\BlogLocaleFieldset;
use Zend\View\Model\ViewModel;

class AmBlogModuleController extends AuthController
{
    protected $tableGateway = null;

    public function setControllerVars()
    {
        $this->tableGateway = $this->sm->get('AmBlog\Model\BlogTable');
        $this->formService  = $this->sm->get('Administrator\Service\AdministratorFormService');
    }

    public function addAction()
    {
        $row = $this->tableGateway->getEntityModel();

        $this->formService
            ->setForm()
            ->addFieldset(BlogFieldset::class, $row)
            ->addLocaleFieldsets(BlogLocaleFieldset::class)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $insertId = $this->tableGateway->save($row);

                return $this->goToSection('blog', array(
                    'action'  => 'edit',
                    'id'      => $insertId
                ));
            }
        }

        return new ViewModel(compact( 'form' ));
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        try {
            $id = (int) $this->params()->fromRoute('id', 0);
            $model = $this->tableGateway->find($id);
        }
        catch (\Exception $ex) {
            return $this->goToSection('blog');
        }

        $this->formService
            ->setForm(BlogForm::class)
            ->addFieldset(BlogFieldset::class, $model)
            ->addLocaleFieldsets(BlogLocaleFieldset::class)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->bind($request->getPost());

            if ($form->isValid()) {

                $this->formService->save();

                return $this->goToEditSection('blog', $id);
            }
        }

        return compact('form');
    }
}