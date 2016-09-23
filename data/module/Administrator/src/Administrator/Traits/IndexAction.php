<?php

namespace Administrator\Traits;


trait IndexAction
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        return $this->serviceLocator->get('Administrator\Service\DatatableService')->init()->run();
    }
}