<?php
namespace Application\Controller;

use Zend\View\Model\ViewModel;

class CompanyController extends ApplicationController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function collaboratorsAction()
    {
        return new ViewModel();
    }
}
