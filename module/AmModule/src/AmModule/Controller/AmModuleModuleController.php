<?php
namespace AmModule\Controller;

use Administrator\Controller\AuthController;

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
        $this->moduleTable = $this->sm->get('AmModule\Model\ModuleTable');

        $this->moduleService = $this->sm->get('AmModule\Service\ModuleService');

        $this->formService = $this->sm->get('Administrator\Service\AdministratorFormService')->setTable($this->moduleTable);
    }


    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        // Sacamos el listado completo de controladores del TableGateway
        $controladores = $this->moduleTable->select();



        // Vamos a la vista de índice con ese array para pintar la tabla.
        return new ViewModel(compact(
            'controladores'
        ));
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
            $gestorControlador = $this->moduleTable->getGestorControlador($id);
        }
        catch (\Exception $ex) {
            return $this->goToSection('controller');
        }

        // Le bindeamos los datos al formulario
        $this->formService->setForm(new ModuleForm())->addFields();

        $form = $this->formService->getForm();
        $form->bind($gestorControlador);

        // Sacamos los datos dle request
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($gestorControlador->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                // Metemos los datos que vamos a guardar
                $this->moduleTable->saveGestorControlador($gestorControlador);
            }
        }

        return array(
            'id' => $id,
            'form' => $form
        );
    }

    /**
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('controller');
        }

        // Borramos con este ID
        $this->moduleTable->deleteGestorControlador($id);

        // Nos vamos al listado general
        return $this->goToSection('controller');
    }
}