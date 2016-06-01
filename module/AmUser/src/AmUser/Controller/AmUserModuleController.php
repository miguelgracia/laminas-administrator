<?php
namespace AmUser\Controller;

use Administrator\Controller\AuthController;

use AmUser\Form\AmUserForm;
use AmUser\Form\UserFieldset;


class AmUserModuleController extends AuthController
{
    protected $tableGateway;
    protected $form;

    public function setControllerVars()
    {
        $this->tableGateway    = $this->sm->get('AmUser\Model\UserTable');
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function addAction()
    {
        $gestorUsuarios = $this->tableGateway->getEntityModel();

        $this->formService
            ->setForm(AmUserForm::class)
            ->addFieldset(UserFieldset::class, $gestorUsuarios)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $gestorUsuarios->password = md5($gestorUsuarios->password);

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
            $gestorUsuarios = $this->tableGateway->find($id);
            $auxGestorUsuarios = clone $gestorUsuarios;
        }
        catch (\Exception $ex) {
            return $this->goToSection('user');
        }

        $this->formService
            ->setForm(AmUserForm::class)
            ->addFieldset(UserFieldset::class, $gestorUsuarios)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $checkPassword =  (bool) $gestorUsuarios->getCheckPassword();

                if (!$checkPassword) {
                    $gestorUsuarios->password = $auxGestorUsuarios->getPassword();
                } else {
                    $gestorUsuarios->password = md5($gestorUsuarios->password);
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