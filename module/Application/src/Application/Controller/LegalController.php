<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LegalController extends AbstractActionController
{
    public function legalAction()
    {
        return new ViewModel();
    }

    public function cookiesAction()
    {
        return new ViewModel();
    }

    public function privacyPolicyAction()
    {
        return new ViewModel();
    }
}
