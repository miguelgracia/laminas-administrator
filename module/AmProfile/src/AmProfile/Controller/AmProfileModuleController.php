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
        $perfiles = $this->perfilTable->all();

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

            $postFieldset = $post->get("AmProfile\\Form\\ProfileFieldset");

            if (!isset($postFieldset['permisos'])) {
                $postFieldset['permisos'] = array();
                $post->set("AmProfile\\Form\\ProfileFieldset", $postFieldset);
            }

            $form->bind($post);

            $permisos = $form->get('AmProfile\Form\ProfileFieldset')->get('permisos')->getValue();

            if ($form->isValid()) {

                $perfil->permisos = json_encode($permisos);

                $insertId = $this->perfilTable->save($perfil);

                return $this->goToSection('profile', array(
                    'action' => 'edit',
                    'id' => $insertId
                ));
            }
        }
        return compact('form');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', false);
        if (!$id) {
            return $this->goToSection('profile');
        }

        try {
            // Sacamos los datos del perfil en concreto
            $perfil = $this->perfilTable->find($id);
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

            $postFieldset = $post->get("AmProfile\\Form\\ProfileFieldset");

            if (!isset($postFieldset['permisos'])) {
                $postFieldset['permisos'] = array();
                $post->set("AmProfile\\Form\\ProfileFieldset", $postFieldset);
            }

            $form->bind($post);

            $permisos = $form->get('AmProfile\Form\ProfileFieldset')->get('permisos')->getValue();

            if ($form->isValid()) {

                $perfil->permisos = json_encode($permisos);

                $this->perfilTable->save($perfil);
            }
        }

        return compact( 'id', 'form' );
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
            'perfil' => $this->perfilTable->find($id)
        );
    }
}