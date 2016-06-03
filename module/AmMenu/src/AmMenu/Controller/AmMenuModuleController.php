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

        $padre = (int) $this->params()->fromRoute('id', 0);

        $fieldset = $this->formService->getBaseFieldset();

        $fieldset->get('padre')->setValue($padre);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $insertId = $this->formService->save();

                return $this->goToSection('menu', array(
                    'action'  => 'edit',
                    'id'      => $insertId[0]
                ));
            }
        }

        $title = 'Nuevo Menú';

        return $this->getAddView(compact( 'form', 'title' ));
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

        $title = 'Edición de Menú';

        return $this->getEditView(compact( 'form', 'title' ));
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