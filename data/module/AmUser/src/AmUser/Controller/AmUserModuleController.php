<?php
namespace AmUser\Controller;

use Administrator\Controller\AuthController;

use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmUser\Form\AmUserForm;
use AmUser\Form\UserFieldset;


class AmUserModuleController extends AuthController
{
    use IndexAction, AddAction, EditAction;
    
    public const FORM_CLASS = AmUserForm::class;

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