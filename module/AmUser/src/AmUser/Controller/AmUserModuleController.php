<?php
namespace AmUser\Controller;

use Administrator\Controller\AuthController;

use AmUser\Form\AmUserForm;
use Zend\Db\Sql\Predicate\Expression;
use Zend\View\Model\ViewModel;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;


class AmUserModuleController extends AuthController
{
    protected $userTable;
    protected $perfilTable;
    protected $form;

    public function setControllerVars()
    {
        $this->userTable = $this->sm->get('AmUser\Model\UserTable');
        $this->perfilTable = $this->sm->get('AmProfile\Model\ProfileTable');
        $this->formService = $this->sm->get('Administrator\Service\AdministratorFormService')->setTable($this->userTable);
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $arrayUsuarios = $this->userTable->fetchAll();

        // Vamos a sacar también mi usuario para destacarlo
        $identity = $this->sm->get('AuthService')->getIdentity();

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
        $this->formService->setForm(new AmUserForm())->addFields();

        $form = $this->formService->getForm();

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();
        if ($request->isPost()) {
            $gestorUsuarios = $this->userTable->getEntityModel();

            $post = $request->getPost();

            $post->validado = 0;

            $form->setInputFilter($gestorUsuarios->getInputFilter())
                ->setData($post);

            if ($form->isValid()) {

                $gestorUsuarios->exchangeArray($form->getData());
                $gestorUsuarios->password = new Expression("md5('$gestorUsuarios->password')");

                // Grabamos lo que teníamos bindeado al form
                $insertId = $this->userTable->saveGestorUsuarios($gestorUsuarios);

                return $this->goToSection('user', array(
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
            return $this->goToSection('user');
        }

        try {
            // Sacamos los datos del usuario en concreto
            $gestorUsuarios = $this->userTable->getGestorUsuarios($id);
            $auxGestorUsuarios = clone $gestorUsuarios;
        }
        catch (\Exception $ex) {
            return $this->goToSection('user');
        }

        // Le bindeamos los datos al formulario y configuramos para que el submit ponga Edit
        $this->formService->setForm(new AmUserForm())->addFields();
        $form = $this->formService->getForm();
        $form->bind($gestorUsuarios);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $post = $request->getPost();

            if (!isset($post->validado)) {
                $post->validado = $gestorUsuarios->validado;
            }
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
                    $gestorUsuarios->password = new Expression("md5('$gestorUsuarios->password')");
                }

                // Grabamos lo que teníamos bindeado al form
                $id = $this->userTable->saveGestorUsuarios($gestorUsuarios);

                return $this->goToSection('user', array(
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
            $this->userTable->deleteGestorUsuarios($id);
        }
        // Nos vamos al listado general
        return $this->goToSection('user');
    }
}