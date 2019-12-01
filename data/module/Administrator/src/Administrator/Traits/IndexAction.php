<?php

namespace Administrator\Traits;


trait IndexAction
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        return $this->datatableService->init()->run();
    }
}