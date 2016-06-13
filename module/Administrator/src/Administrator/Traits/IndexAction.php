<?php

namespace Administrator\Traits;


trait IndexAction
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $datatable = $this->serviceLocator->get('Administrator\Service\DatatableService');

        $datatable->init();

        return $datatable->run();
    }
}