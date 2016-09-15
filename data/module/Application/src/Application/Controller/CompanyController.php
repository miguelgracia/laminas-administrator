<?php
namespace Application\Controller;

use Zend\View\Model\ViewModel;

class CompanyController extends ApplicationController
{
    public function indexAction()
    {
        $menu = $this->menu;

        if (isset($menu->rows->company) and $menu->rows->company->active == 1) {

            $menuLang = $menu->locale->{$this->lang};

            $this->headTitleHelper->append($menuLang[$menu->rows->company->id]->name);

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

        if (isset($menu->rows->{"company/colaborators"}) and $menu->rows->{"company/colaborators"}->active == 1) {

            $menuLang = $menu->locale->{$this->lang};

            $this->headTitleHelper->append($menuLang[$menu->rows->company->id]->name);
            $this->headTitleHelper->append($menuLang[$menu->rows->{"company/colaborators"}->id]->name);

            return new ViewModel(array(
                'menu'     => $menu,
                'lang'     => $this->lang,
                'partners' => $this->api->partner->getData($this->lang)
            ));
        }

        $this->getResponse()->setStatusCode(404);
    }
}
