<?php
namespace AmModule\Controller;

use Administrator\Controller\AuthController;

use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmModule\Form\ModuleFieldset;
use AmModule\Form\ModuleForm;

class AmModuleModuleController extends AuthController
{
    use IndexAction, EditAction;

    protected $form = ModuleForm::class;

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