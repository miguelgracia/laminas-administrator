<?php
namespace Gestor\Controller;

use Zend\View\Model\ViewModel;

use Gestor\Model\EntradaMenu;  // Para poder acceder a los objetos del modelo
use Gestor\Form\EntradaMenuForm;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;

class EntradaMenuController extends AuthController implements ControllerInterface
{
    protected $entradaMenuTable;

    public function setControllerVars()
    {
        $this->entradaMenuTable = $this->sm->get('Gestor\Model\EntradaMenuTable');
        $this->formService      = $this->sm->get('Gestor\Service\GestorFormService')->setTable($this->entradaMenuTable);
    }

    public function indexAction()
    {
        $arrayListado = $this->entradaMenuTable->fetchAllOrdenados();

        return new ViewModel(array(
            'entradas' => $arrayListado,
        ));
    }

    public function addAction()
    {
        $this->formService->setForm(new EntradaMenuForm())->addFields();

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

                $insertId = $this->entradaMenuTable->saveEntradaMenu($entradaMenu); // Grabamos lo que teníamos bindeado al form

                return $this->goToSection('entradamenu', array(
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
            return $this->goToSection('entradamenu');
        }

        // Sacamos los datos de una entrada en concreto
        try {
            $entradaMenu = $this->entradaMenuTable->getEntradaMenu($id);
        } catch (\Exception $ex) {
            return $this->goToSection('entradamenu');
        }

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();

        $this->formService->setForm(new EntradaMenuForm())->addFields();

        $form = $this->formService->getForm();

        // Le bindeamos los datos al formulario
        $form->bind($entradaMenu);

        if ($request->isPost()) {

            $post = $request->getPost();

            $entradaMenu->tieneEnlace = (int) ($post['idControlador'] != null and $post['idControlador'] != '');

            $form->setInputFilter($entradaMenu->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {
                // Grabamos lo que teníamos bindeado al form
                $this->entradaMenuTable->saveEntradaMenu($entradaMenu);

                return $this->goToSection('entradamenu',array(
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

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id != 0) {
            // Borramos con este ID
            $this->entradaMenuTable->deleteEntradaMenu($id);
        }

        // Nos vamos al listado general
        return $this->goToSection('entradamenu');
    }
}