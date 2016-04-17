<?php

namespace Gestor\Controller;

use Zend\View\Model\ViewModel;

class ProductoController extends AuthController implements ControllerInterface
{
    public function setControllerVars()
    {

    }

    public function indexAction()
    {
        return new ViewModel(array(
            'eh' => 0,
        ));
    }
}