<?php
namespace AmProfile\Controller;

use Administrator\Controller\AuthController;
use AmProfile\Form\ProfileFieldset;
use AmProfile\Form\ProfileForm;
use Zend\View\Model\ViewModel;


class AmProfileModuleController extends AuthController
{
    protected $perfilTable;

    public function setControllerVars()
    {
        $this->perfilTable = $this->sm->get('AmProfile\Model\ProfileTable');
        $this->formService = $this->sm->get('Administrator\Service\AdministratorFormService')->setTable($this->perfilTable);
    }

    public function indexAction()
    {
        $perfiles = $this->perfilTable->fetchAll();

        return new ViewModel(compact('perfiles'));
    }

    public function addAction()
    {
        $perfil = $this->perfilTable->getEntityModel();

        $fieldset = new ProfileFieldset($this->serviceLocator,$perfil,$this->perfilTable);

        $this->formService
            ->setForm(new ProfileForm())
            ->addFieldset($fieldset)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $post = $request->getPost();

            $post->permisos = $this->params()->fromPost('permisos', array());

            $form->bind($post);

            if ($form->isValid()) {
                //  Si funciona, metemos en el Model a través de su papá AdministratorModel,
                // que implementa un exchangeArray genérico que nos vale para
                // cualquier colección de datos.

                $perfil->exchangeArray($form->getData());
                $perfil->permisos = json_encode(isset($perfil->permisos) ? $perfil->permisos : array());

                // Ahora ya sí, llamamos al método que hace el INSERT específico
                $insertId = $this->perfilTable->savePerfil($perfil);

                // Nos vamos a la ruta de edición de perfil
                return $this->goToSection('profile', array(
                    'action' => 'edit',
                    'id' => $insertId
                ));
            }
        }
        return array(
            'form' => $form,
        );
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', false);
        if (!$id) {
            return $this->goToSection('profile');
        }

        try {
            // Sacamos los datos del perfil en concreto
            $perfil = $this->perfilTable->getPerfil($id);
            $perfil->permisos = $perfil->getPermisos();

        } catch (\Exception $ex) {
            return $this->goToSection('profile');
        }

        $fieldset = new ProfileFieldset($this->serviceLocator,$perfil,$this->perfilTable);

        $this->formService
            ->setForm(new ProfileForm())
            ->addFieldset($fieldset)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $post = $request->getPost();

            $form->bind($post);

            $post->permisos = $this->params()->fromPost('permisos', array());

            if ($form->isValid()) {

                $perfil->permisos = json_encode($perfil->permisos);
                $this->perfilTable->savePerfil($perfil);
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('profile');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->perfilTable->deletePerfil($id);
            }

            // Redirect to list of perfiles
            return $this->goToSection('profile');
        }

        return array(
            'id'    => $id,
            'perfil' => $this->perfilTable->getPerfil($id)
        );
    }
}