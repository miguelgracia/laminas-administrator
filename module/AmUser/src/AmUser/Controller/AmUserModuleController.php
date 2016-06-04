<?php
namespace AmUser\Controller;

use Administrator\Controller\AuthController;

use AmUser\Form\AmUserForm;
use AmUser\Form\UserFieldset;


class AmUserModuleController extends AuthController
{
    protected $form = AmUserForm::class;
    
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