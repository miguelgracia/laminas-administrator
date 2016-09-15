<?php
namespace AmMenu\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;

use AmMenu\Form\MenuForm;


class AmMenuModuleController extends AuthController
{
    use AddAction, EditAction;

    protected $form = MenuForm::class;

    public function indexAction()
    {
        $entradas = $this->tableGateway->fetchAllOrdenados();

        return compact('entradas');
    }

    public function saveOrderAction()
    {
        $request = $this->getRequest();
        $result = array();

        if ($request->isPost()) {
            $menuIds = $this->params()->fromPost('elements');
            foreach ($menuIds as $index => $id) {
                $this->tableGateway->save(array(
                    'order' => (int) $index + 1
                ),$id);
            }
            echo json_encode($result);
            die;
        }

        return $this->goToSection('home');
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id != 0) {
            // Borramos con este ID
            $this->tableGateway->deleteEntradaMenu($id);
        }

        // Nos vamos al listado general
        return $this->goToSection('menu');
    }
}