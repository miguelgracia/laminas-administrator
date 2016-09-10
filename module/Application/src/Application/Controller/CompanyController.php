<?php
namespace Application\Controller;

use Zend\View\Model\ViewModel;

class CompanyController extends ApplicationController
{
    public function indexAction()
    {
        $menu = $this->menu;

        if ($menu->rows->company->active == 1) {

            $viewParams = array(
                'menu' => $menu,
                'lang' => $this->lang
            );

            if ($menu->rows->{"company/colaborators"}->active == 1) {
                $viewParams['partners'] = $this->api->partner->getData($this->lang);
            }

            return new ViewModel($viewParams);
        }

        $this->getResponse()->setStatusCode(404);
    }

    public function collaboratorsAction()
    {
        $menu = $this->menu;

        if ($menu->rows->{"company/colaborators"}->active == 1) {
            return new ViewModel(array(
                'menu'     => $menu,
                'lang'     => $this->lang,
                'partners' => $this->api->partner->getData($this->lang)
            ));
        }

        $this->getResponse()->setStatusCode(404);
    }
}
