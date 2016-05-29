<?php

namespace AmBlogCategory\Controller;

use Administrator\Controller\AuthController;
use AmBlogCategory\Form\BlogCategoryFieldset;
use AmBlogCategory\Form\BlogCategoryForm;
use Zend\View\Model\ViewModel;

class AmBlogCategoryModuleController extends AuthController
{

    protected $blogcategoryTable = null;

    public function setControllerVars()
    {
        $this->tableGateway = $this->sm->get('AmBlogCategory\Model\BlogCategoryTable');
        $this->formService  = $this->sm->get('Administrator\Service\AdministratorFormService')->setTable($this->tableGateway);
    }

    public function addAction()
    {
        $model = $this->tableGateway->getEntityModel();

        $fieldset = new BlogCategoryFieldset($this->serviceLocator,$model,$this->tableGateway);

        $this->formService
            ->setForm(new BlogCategoryForm())
            ->addFieldset($fieldset)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $post = $request->getPost();

            $form->bind($post);

            if ($form->isValid()) {

                $insertId = $this->tableGateway->save($model);

                return $this->goToSection('blog-category', array(
                    'action' => 'edit',
                    'id' => $insertId
                ));
            }
        }
        return compact('form');
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
            return $this->goToSection('blog-category');
        }

        $fieldset = new BlogCategoryFieldset($this->serviceLocator, $model, $this->tableGateway);

        $this->formService
            ->setForm(new BlogCategoryForm())
            ->addFieldset($fieldset)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $this->tableGateway->save($model);
            }
        }

        return compact('id', 'form');
    }
}