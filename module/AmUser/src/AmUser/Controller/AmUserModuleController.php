<?php
namespace AmUser\Controller;

use Administrator\Controller\AuthController;

use AmUser\Form\AmUserForm;
use AmUser\Form\UserFieldset;
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
        $this->userTable    = $this->sm->get('AmUser\Model\UserTable');
        $this->perfilTable  = $this->sm->get('AmProfile\Model\ProfileTable');
        $this->formService  = $this->sm->get('Administrator\Service\AdministratorFormService');
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function addAction()
    {
        $gestorUsuarios = $this->userTable->getEntityModel();

        $this->formService
            ->setForm(AmUserForm::class)
            ->addFieldset(UserFieldset::class, $gestorUsuarios)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($request->getPost());

            if ($form->isValid()) {

                $gestorUsuarios->password = md5($gestorUsuarios->password);

                $insertId = $this->userTable->save($gestorUsuarios);

                return $this->goToSection('user', array(
                    'action' => 'edit',
                    'id' => $insertId
                ));
            }
        }

        $messages = array();

        return compact('form', 'messages');
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
            $gestorUsuarios = $this->userTable->find($id);
            $auxGestorUsuarios = clone $gestorUsuarios;
        }
        catch (\Exception $ex) {
            return $this->goToSection('user');
        }

        $this->formService
            ->setForm(AmUserForm::class)
            ->addFieldset(UserFieldset::class, $gestorUsuarios)
            ->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $post = $request->getPost();

            $form->bind($post);

            if ($form->isValid()) {

                $checkPassword =  (bool) $gestorUsuarios->getCheckPassword();

                if (!$checkPassword) {
                    $gestorUsuarios->password = $auxGestorUsuarios->getPassword();
                } else {
                    $gestorUsuarios->password = new Expression("md5('$gestorUsuarios->password')");
                }

                $this->userTable->save($gestorUsuarios);
            }
        }

        return compact('form');
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