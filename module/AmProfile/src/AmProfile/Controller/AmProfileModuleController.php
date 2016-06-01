<?php
namespace AmProfile\Controller;

use Administrator\Controller\AuthController;
use AmProfile\Form\ProfileFieldset;
use AmProfile\Form\ProfileForm;

class AmProfileModuleController extends AuthController
{
    public function addAction()
    {
        $perfil = $this->tableGateway->getEntityModel();

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

                $insertId = $this->formService->save();

                return $this->goToSection('profile', array(
                    'action' => 'edit',
                    'id' => $insertId[0]
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

            $perfil = $this->tableGateway->find($id);
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

                $this->tableGateway->save($perfil);
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
                $this->tableGateway->deletePerfil($id);
            }

            // Redirect to list of perfiles
            return $this->goToSection('profile');
        }

        return array(
            'id'    => $id,
            'perfil' => $this->tableGateway->find($id)
        );
    }
}