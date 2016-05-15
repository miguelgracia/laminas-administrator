<?php
namespace AmMenu\Controller;

use Administrator\Controller\AuthController;
use AmMenu\Form\MenuForm;
use Zend\View\Model\ViewModel;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;

class AmMenuModuleController extends AuthController
{
    protected $entradaMenuTable;

    public function setControllerVars()
    {
        $this->entradaMenuTable = $this->sm->get('AmMenu\Model\MenuTable');
        $this->formService      = $this->sm->get('Administrator\Service\AdministratorFormService')->setTable($this->entradaMenuTable);
    }

    public function indexAction()
    {
        $entradas = $this->entradaMenuTable->fetchAllOrdenados();


        return new ViewModel(compact('entradas'));
    }

    public function addAction()
    {
        $this->formService->setForm(new MenuForm())->addFields();

        $form = $this->formService->getForm();

        // Asignamos "padre" y "orden"
        $padre = (int) $this->params()->fromRoute('id', -1);
        if ($padre == 0) { $padre = "-1";}
        $form->get('padre')->setValue($padre);

        $orden = (int) $this->params()->fromRoute('data', 0);
        $orden++; // Como minimo debe ser orden 1
        $form->get('orden')->setValue($orden);

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();
        if ($request->isPost()) {

            $entradaMenu = $this->entradaMenuTable->getEntityModel();

            $post = $request->getPost();
            $post['tieneEnlace'] = (int) ($post['idControlador'] != null and $post['idControlador'] != '');

            $form->setInputFilter($entradaMenu->getInputFilter());
            $form->bind($post);

            if ($form->isValid()) {
                // Metemos los datos que vamos a guardar
                $entradaMenu->exchangeArray($post);

                $insertId = $this->entradaMenuTable->saveEntradaMenu($entradaMenu); // Grabamos lo que ten�amos bindeado al form

                return $this->goToSection('menu', array(
                    'action'  => 'edit',
                    'id'      => $insertId
                ));
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'padre' => $padre,
        ));
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('menu');
        }

        // Sacamos los datos de una entrada en concreto
        try {
            $entradaMenu = $this->entradaMenuTable->getEntradaMenu($id);
        } catch (\Exception $ex) {
            return $this->goToSection('menu');
        }

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();

        $this->formService->setForm(new MenuForm())->addFields();

        $form = $this->formService->getForm();

        // Le bindeamos los datos al formulario
        $form->bind($entradaMenu);

        if ($request->isPost()) {

            $post = $request->getPost();

            $entradaMenu->tieneEnlace = (int) ($post['idControlador'] != null and $post['idControlador'] != '');

            $form->setInputFilter($entradaMenu->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {
                // Grabamos lo que ten�amos bindeado al form
                $this->entradaMenuTable->saveEntradaMenu($entradaMenu);

                return $this->goToSection('menu',array(
                    'action' => 'edit',
                    'id' => $id
                ));
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
                $this->entradaMenuTable->save(array(
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
            $this->entradaMenuTable->deleteEntradaMenu($id);
        }

        // Nos vamos al listado general
        return $this->goToSection('menu');
    }
}