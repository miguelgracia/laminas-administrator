<?php
namespace Gestor\Controller;


class EquiposController extends AuthController implements ControllerInterface
{
    protected $datatable;

    public function setControllerVars()
    {
        $this->datatable = $this->sm->get('Gestor\Service\DatatableService');
    }

    public function indexAction()
    {

    }

    public function addAction()
    {

    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }
}