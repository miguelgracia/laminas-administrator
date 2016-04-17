<?php
namespace Gestor\Controller;

use Zend\View\Model\ViewModel;

use Gestor\Model\Perfil;  // Para poder acceder a los objetos del modelo

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;

use Gestor\Form\PerfilForm;

class PerfilController extends AuthController implements ControllerInterface
{
    protected $perfilTable;

    public function setControllerVars()
    {
        $this->perfilTable = $this->sm->get('Gestor\Model\PerfilTable');
        $this->formService = $this->sm->get('Gestor\Service\GestorFormService')->setTable($this->perfilTable);
    }

    public function indexAction()
    {
        // Solo te deja ver los perfiles que dependen del tuyo en caso de que no seas admin
        $misPermisos = $this->sm->get('Gestor\Factory\PermisosCheckerFactory');

        $arrayListado = $misPermisos->isAdmin()
            ? $this->perfilTable->fetchAll()
            : $this->perfilTable->fetchHijos($misPermisos->idPerfil);

        return new ViewModel(array(
            'perfiles' => $arrayListado,
            'miPerfil' => $misPermisos->idPerfil,
        ));
    }

    public function addAction()
    {
        $this->formService->setForm(new PerfilForm())->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $perfil = $this->perfilTable->getEntityModel();
            $form->setInputFilter($perfil->getInputFilter());
            $form->bind($request->getPost());
            if ($form->isValid()) {
                //  Si funciona, metemos en el Model a través de su papá GestorModel, que implementa un exchangeArray
                // genérico que nos vale para cualquier colección de datos.
                $perfil->exchangeArray($form->getData());

                // Ahora ya sí, llamamos al método que hace el INSERT específico
                $insertId = $this->perfilTable->savePerfil($perfil);

                // Nos vamos a la ruta de edición de perfil
                return $this->goToSection('perfil', array(
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
            return $this->goToSection('perfil');
        }

        try {
            // Sacamos los datos del perfil en concreto
            $perfil = $this->perfilTable->getPerfil($id);
        } catch (\Exception $ex) {
            return $this->goToSection('perfil');
        }

        $this->formService->setForm(new PerfilForm())->addFields();

        $form = $this->formService->getForm();

        // Le bindeamos los datos al formulario
        $form->bind($perfil);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter($perfil->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                // Metemos los datos que vamos a guardar
                //$gestorControlador->exchangeArray($form->getData());
                $this->perfilTable->savePerfil($perfil);

                return $this->goToSection('perfil',array(
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
            return $this->goToSection('perfil');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->perfilTable->deletePerfil($id);
            }

            // Redirect to list of perfiles
            return $this->goToSection('perfil');
        }

        return array(
            'id'    => $id,
            'perfil' => $this->perfilTable->getPerfil($id)
        );
    }
}