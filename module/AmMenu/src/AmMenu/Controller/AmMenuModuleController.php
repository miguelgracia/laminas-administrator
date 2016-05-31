<?php
namespace AmMenu\Controller;

use Administrator\Controller\AuthController;
use AmMenu\Form\MenuFieldset;
use AmMenu\Form\MenuForm;
use Zend\View\Model\ViewModel;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;

class AmMenuModuleController extends AuthController
{
    protected $tableGateway;

    public function setControllerVars()
    {
        $this->tableGateway = $this->sm->get('AmMenu\Model\MenuTable');
        $this->formService  = $this->sm->get('Administrator\Service\AdministratorFormService');
    }

    public function indexAction()
    {
        $entradas = $this->tableGateway->fetchAllOrdenados();

        return compact('entradas');
    }

    public function addAction()
    {
        $entradaMenu = $this->tableGateway->getEntityModel();

        $this->formService
            ->setForm(MenuForm::class)
            ->addFieldset(MenuFieldset::class, $entradaMenu)
            ->addFields();

        $form = $this->formService->getForm();

        // Asignamos "padre" y "orden"
        $padre = (int) $this->params()->fromRoute('id', 0) ?: -1;

        $fieldset = $this->formService->getBaseFieldset();

        $fieldset->get('padre')->setValue($padre);

        $orden = (int) ($this->params()->fromRoute('data', 0)) + 1; // Como minimo debe ser orden 1

        $fieldset->get('orden')->setValue($orden);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $insertId = $this->tableGateway->save($entradaMenu);

                return $this->goToSection('menu', array(
                    'action'  => 'edit',
                    'id'      => $insertId
                ));
            }
        }

        return new ViewModel(compact( 'form' , 'padre' ));
    }

    public function editAction()
    {
        try {
            $id = (int) $this->params()->fromRoute('id', 0);
            $entradaMenu = $this->tableGateway->find($id);
        } catch (\Exception $ex) {
            return $this->goToSection('menu');
        }

        $this->formService
            ->setForm(MenuForm::class)
            ->addFieldset(MenuFieldset::class, $entradaMenu)
            ->addFields();

        $request = $this->getRequest();

        $form = $this->formService->getForm();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $this->tableGateway->save($entradaMenu);
            }
        }

        return new ViewModel(array(
            'id'          => $id,
            'form'        => $form
        ));
    }

    public function saveOrderAction()
    {
        $request = $this->getRequest();
        $result = array();

        if ($request->isPost()) {
            $menuIds = $this->params()->fromPost('elements');
            foreach ($menuIds as $index => $id) {
                $this->tableGateway->save(array(
                    'orden' => (int) $index + 1
                ),$id);
            }
            echo json_encode($result);
            die;
        }

        return $this->goToSection('home');
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id != 0) {
            // Borramos con este ID
            $this->tableGateway->deleteEntradaMenu($id);
        }

        // Nos vamos al listado general
        return $this->goToSection('menu');
    }
}