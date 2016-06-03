<?php
namespace AmModule\Controller;

use Administrator\Controller\AuthController;

use AmModule\Form\ModuleFieldset;
use AmModule\Form\ModuleForm;

class AmModuleModuleController extends AuthController
{
    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        try {
            // Sacamos los datos del usuario en concreto
            $id = (int) $this->params()->fromRoute('id', 0); // Lo sacamos de la ruta
            // Sacamos la información del controlador definido por este id
            $model = $this->tableGateway->find($id);
        }
        catch (\Exception $ex) {
            return $this->goToSection('module');
        }

        $this->formService
            ->setForm(ModuleForm::class)
            ->addFieldset(ModuleFieldset::class, $model)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $this->formService->save();
            }
        }

        $title = 'Edición de Módulo';

        return $this->getEditView(compact( 'form', 'title' ));
    }

    /**
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('module');
        }

        // Borramos con este ID
        $this->tableGateway->deleteRow($id);

        // Nos vamos al listado general
        return $this->goToSection('module');
    }
}