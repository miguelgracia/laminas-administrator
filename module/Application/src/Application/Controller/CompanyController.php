<?php
namespace Application\Controller;

use Zend\View\Model\ViewModel;

class CompanyController extends ApplicationController
{
    public function indexAction()
    {
        return new ViewModel(array(
            'menu' => $this->menu,
            'lang' => $this->lang
        ));
    }

    public function collaboratorsAction()
    {
        return new ViewModel(array(
            'lang' => $this->lang
        ));
    }
}
