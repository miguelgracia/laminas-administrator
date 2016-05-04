<?php
namespace AmProfile\Controller;

use Administrator\Controller\AuthController;
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
        $this->formService->setForm(new ProfileForm())->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $perfil = $this->perfilTable->getEntityModel();
            $form->setInputFilter($perfil->getInputFilter());
            $form->bind($request->getPost());
            if ($form->isValid()) {
                //  Si funciona, metemos en el Model a trav�s de su pap� GestorModel, que implementa un exchangeArray
                // gen�rico que nos vale para cualquier colecci�n de datos.
                $perfil->exchangeArray($form->getData());

                // Ahora ya s�, llamamos al m�todo que hace el INSERT espec�fico
                $insertId = $this->perfilTable->savePerfil($perfil);

                // Nos vamos a la ruta de edici�n de perfil
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
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('profile');
        }

        try {
            // Sacamos los datos del perfil en concreto
            $perfil = $this->perfilTable->getPerfil($id);
        } catch (\Exception $ex) {
            return $this->goToSection('profile');
        }

        $this->formService->setForm(new ProfileForm())->addFields();

        $form = $this->formService->getForm();

        // Le bindeamos los datos al formulario
        $form->bind($perfil);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter($perfil->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                // Metemos los datos que vamos a guardar

                $this->perfilTable->savePerfil($perfil);

                return $this->goToSection('profile',array(
                    'action'  => 'edit',
                    'id'      => $request->getPost('id')
                ));
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