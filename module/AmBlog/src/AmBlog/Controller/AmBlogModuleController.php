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
        $this->formService  = $this->sm->get('Administrator\Service\AdministratorFormService')->setTable($this->tableGateway);
    }

    public function addAction()
    {
        $row = $this->tableGateway->getEntityModel();

        $fieldset = new BlogFieldset($this->serviceLocator,$row,$this->tableGateway);

        $this->formService
            ->setForm()
            ->addFieldset($fieldset)
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

        $fieldset = new BlogFieldset($this->serviceLocator, $model, $this->tableGateway);

        $localeTableGateway = $this->sm->get('AmBlog\Model\BlogLocaleTable');

        $localeModels = $localeTableGateway->findLocales($id);

        $this->formService
            ->setForm(new BlogForm())
            ->addFieldset($fieldset);

        $thisLocaleModels = array();

        foreach ($localeModels as $localModel) {

            $localModel->blogEntriesId = $id;

            $thisLocaleModels[] = $localModel;

            $localeFieldset = new BlogLocaleFieldset($this->serviceLocator, $localModel, $localeTableGateway);

            $this->formService->addLocaleFieldset($localeFieldset);
        }

        $this->formService->addFields();

        $this->formService->getLocaleModels();


        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $this->tableGateway->save($model);

                foreach ($thisLocaleModels as $l) {
                    $l->id = $localeTableGateway->save($l);
                }

                return $this->goToEditSection('blog', $id);
            }
        }

        return compact('id', 'form');
    }


}

