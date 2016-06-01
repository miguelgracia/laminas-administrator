<?php
namespace AmUser\Controller;

use Administrator\Controller\AuthController;

use AmUser\Form\AmUserForm;
use AmUser\Form\UserFieldset;


class AmUserModuleController extends AuthController
{
    /**
     * @return array|\Zend\Http\Response
     */
    public function addAction()
    {
        $model = $this->tableGateway->getEntityModel();

        $this->formService
            ->setForm(AmUserForm::class)
            ->addFieldset(UserFieldset::class, $model)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $model->password = md5($model->password);

                $insertId = $this->formService->save();

                return $this->goToSection('user', array(
                    'action' => 'edit',
                    'id' => $insertId[0]
                ));
            }
        }

        $title = 'Nuevo Usuario';

        return $this->getAddView(compact( 'form', 'title' ));
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        try {
            $id = (int) $this->params()->fromRoute('id', 0);
            $model = $this->tableGateway->find($id);
            $auxModel = clone $model;
        }
        catch (\Exception $ex) {
            return $this->goToSection('user');
        }

        $this->formService
            ->setForm(AmUserForm::class)
            ->addFieldset(UserFieldset::class, $model)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $checkPassword =  (bool) $model->getCheckPassword();

                if (!$checkPassword) {
                    $model->password = $auxModel->getPassword();
                } else {
                    $model->password = md5($model->password);
                }

                $this->formService->save();
            }
        }

        $title = 'Edición de Usuario';

        return $this->getEditView(compact( 'form', 'title' ));
    }

    /**
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id != 0) {
            // Borramos con este ID
            $this->tableGateway->deleteGestorUsuarios($id);
        }
        // Nos vamos al listado general
        return $this->goToSection('user');
    }
}