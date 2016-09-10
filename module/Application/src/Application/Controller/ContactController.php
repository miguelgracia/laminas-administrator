<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class ContactController extends ApplicationController
{
    public function indexAction()
    {
        return new ViewModel(array(
            'lang' => $this->lang,
            'appData' => $this->appData
        ));
    }
}
