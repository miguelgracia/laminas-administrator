<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CompanyController extends AbstractActionController
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
