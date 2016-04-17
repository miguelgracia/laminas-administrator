<?php
namespace Gestor\Controller;

use Zend\View\Model\ViewModel;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;

use Gestor\Form\GestorUsuariosForm;

class GestorUsuariosController extends AuthController implements ControllerInterface
{
    protected $gestorUsuariosTable;
    protected $perfilTable;
    protected $form;

    public function setControllerVars()
    {
        $this->gestorUsuariosTable = $this->sm->get('Gestor\Model\GestorUsuariosTable');
        $this->perfilTable = $this->sm->get('Gestor\Model\PerfilTable');
        $this->formService = $this->sm->get('Gestor\Service\GestorFormService')->setTable($this->gestorUsuariosTable);
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {

        $misPermisos = $this->sm->get('Gestor\Factory\PermisosCheckerFactory');
        // Solo vamos a mostrar todos los usuarios si eres admin
        if ($misPermisos->isAdmin()) {
            $arrayUsuarios = $this->gestorUsuariosTable->fetchAll();
        } else {
            // Si no eres admin, vamos a mostrar solo los usuarios de perfiles hijos de tu perfil
            $arrayUsuarios = $this->gestorUsuariosTable->fetchHijos($misPermisos->idPerfil, $this->perfilTable);
        }

        // Vamos a sacar también mi usuario para destacarlo
        //$sessionService = $this->sm->get('Gestor\Service\SessionServiceInterface');
        $authService = $this->sm->get('AuthService');
        $identity = $authService->getIdentity();

        return new ViewModel(array(
            'usuarios' => $arrayUsuarios,
            'quienSoy' => $identity['user'],
        ));
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function addAction()
    {
        // Ya hemos sacado las cosas que necesitamos
        $this->formService->setForm(new GestorUsuariosForm())->addFields();

        $form = $this->formService->getForm();

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();
        if ($request->isPost()) {
            $gestorUsuarios = $this->gestorUsuariosTable->getEntityModel();

            $form->setInputFilter($gestorUsuarios->getInputFilter())
                ->setData($request->getPost());

            if ($form->isValid()) {
                $gestorUsuarios->exchangeArray($form->getData());
                // Grabamos lo que teníamos bindeado al form
                $insertId = $this->gestorUsuariosTable->saveGestorUsuarios($gestorUsuarios);

                return $this->goToSection('gestorusuarios', array(
                    'action' => 'edit',
                    'id' => $insertId
                ));
            }
        }

        return array(
            'form' => $form,
            'messages' => array(),
        );
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('gestorusuarios');
        }

        try {
            // Sacamos los datos del usuario en concreto
            $gestorUsuarios = $this->gestorUsuariosTable->getGestorUsuarios($id);
            $auxGestorUsuarios = clone $gestorUsuarios;
        }
        catch (\Exception $ex) {
            return $this->goToSection('gestorusuarios');
        }

        // Le bindeamos los datos al formulario y configuramos para que el submit ponga Edit
        $this->formService->setForm(new GestorUsuariosForm())->addFields();
        $form = $this->formService->getForm();
        $form->bind($gestorUsuarios);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $post = $request->getPost();
            $checkPassword = (bool) $post['checkPassword'];

            $gestorUsuarios->setCheckPassword($checkPassword);

            $form->setInputFilter($gestorUsuarios->getInputFilter());

            $form->setData($post);

            if ($form->isValid()) {

                unset($gestorUsuarios->checkPassword);
                unset($gestorUsuarios->password2);

                if (!$checkPassword) {
                    $gestorUsuarios->password = $auxGestorUsuarios->getPassword();
                } else {
                    $gestorUsuarios->password = md5($gestorUsuarios->password);
                }

                // Grabamos lo que teníamos bindeado al form
                $id = $this->gestorUsuariosTable->saveGestorUsuarios($gestorUsuarios);

                return $this->goToSection('gestorusuarios', array(
                    'action' => 'edit',
                    'id' => $id
                ));
            }
        }

        return array(
            'id'        => $id,
            'form'      => $form,
            'user'      => $gestorUsuarios
        );
    }

    /**
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id != 0) {
            // Borramos con este ID
            $this->gestorUsuariosTable->deleteGestorUsuarios($id);
        }
        // Nos vamos al listado general
        return $this->goToSection('gestorusuarios');
    }
}