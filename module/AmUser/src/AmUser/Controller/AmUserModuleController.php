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
        $this->formService  = $this->sm->get('Administrator\Service\AdministratorFormService')->setTable($this->userTable);
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function addAction()
    {
        $gestorUsuarios = $this->userTable->getEntityModel();

        $fieldset = new UserFieldset($this->serviceLocator,$gestorUsuarios,$this->userTable);

        $this->formService
            ->setForm(new AmUserForm())
            ->addFieldset($fieldset)
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
            $gestorUsuarios = $this->userTable->find($id);
            $auxGestorUsuarios = clone $gestorUsuarios;
        }
        catch (\Exception $ex) {
            return $this->goToSection('user');
        }

        $fieldset = new UserFieldset($this->serviceLocator, $gestorUsuarios, $this->userTable);

        $this->formService
            ->setForm(new AmUserForm())
            ->addFieldset($fieldset)
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