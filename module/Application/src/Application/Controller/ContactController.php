<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class ContactController extends ApplicationController
{
    public function indexAction()
    {
        $menu = $this->menu;

        if (isset($menu->rows->contact) and $menu->rows->contact->active == 1) {
            return new ViewModel(array(
                'menu' => $this->menu,
                'lang' => $this->lang,
                'appData' => $this->appData
            ));
        }

        $this->getResponse()->setStatusCode(404);
    }
}
