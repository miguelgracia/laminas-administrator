<?php
namespace Application\Controller;

use Zend\View\Model\ViewModel;

class CompanyController extends ApplicationController
{
    public function indexAction()
    {
        $menu = $this->menu;

        if ($menu->rows->company->active == 1) {
            return new ViewModel(array(
                'menu' => $menu,
                'lang' => $this->lang
            ));
        }

        $this->getResponse()->setStatusCode(404);
    }

    public function collaboratorsAction()
    {
        return new ViewModel(array(
            'lang' => $this->lang
        ));
    }
}
