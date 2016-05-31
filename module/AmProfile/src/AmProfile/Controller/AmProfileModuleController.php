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
        $this->formService = $this->sm->get('Administrator\Service\AdministratorFormService');
    }

    public function addAction()
    {
        $perfil = $this->perfilTable->getEntityModel();

        $this->formService
            ->setForm(ProfileForm::class)
            ->addFieldset(ProfileFieldset::class, $perfil)
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
        $title = 'Nuevo Perfil';

        return $this->getAddView(compact( 'form', 'title' ));
    }

    public function editAction()
    {
        try {
            $id = (int) $this->params()->fromRoute('id', 0);

            $perfil = $this->perfilTable->find($id);
            $perfil->permisos = $perfil->getPermisos();

        } catch (\Exception $ex) {
            return $this->goToSection('profile');
        }

        $this->formService
            ->setForm(ProfileForm::class)
            ->addFieldset(ProfileFieldset::class, $perfil)
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

        $title = 'Edición de Perfil';

        return $this->getAddView(compact( 'form', 'title' ));
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