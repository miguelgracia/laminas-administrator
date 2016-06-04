<?php

namespace Administrator\Traits;


trait IndexAction
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $datatable = $this->sm->get('Administrator\Service\DatatableService');

        $datatable->init();

        return $datatable->run();
    }
}