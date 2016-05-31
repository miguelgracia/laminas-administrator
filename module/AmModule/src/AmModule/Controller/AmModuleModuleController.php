<?php
namespace AmModule\Controller;

use Administrator\Controller\AuthController;

use AmModule\Form\ModuleFieldset;
use AmModule\Form\ModuleForm;
use Zend\View\Model\ViewModel;


class AmModuleModuleController extends AuthController
{
    protected $moduleTable;
    protected $moduleService;
    protected $form;

    /**
     * Este método está definido en ControllerInterface y SIEMPRE va a existir. Se lo llamará cuando se crea
     * un controlador, de modo que se hace un setup de aquello que específicamente necesite
     * el controlador.
     */
    public function setControllerVars()
    {
        // Como vamos a acceder a la tabla de controladores sacamos el Model con el Service Manager
        $this->moduleTable   = $this->sm->get('AmModule\Model\ModuleTable');
        $this->moduleService = $this->sm->get('AmModule\Service\ModuleService');
        $this->formService   = $this->sm->get('Administrator\Service\AdministratorFormService');
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        try {
            // Sacamos los datos del usuario en concreto
            $id = (int) $this->params()->fromRoute('id', 0); // Lo sacamos de la ruta
            // Sacamos la información del controlador definido por este id
            $gestorControlador = $this->moduleTable->find($id);
        }
        catch (\Exception $ex) {
            return $this->goToSection('module');
        }

        $this->formService
            ->setForm(ModuleForm::class)
            ->addFieldset(ModuleFieldset::class, $gestorControlador)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $this->moduleTable->save($gestorControlador);
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
        $this->moduleTable->deleteRow($id);

        // Nos vamos al listado general
        return $this->goToSection('module');
    }
}