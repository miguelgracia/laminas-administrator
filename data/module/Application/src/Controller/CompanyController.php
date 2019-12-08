<?php

namespace Application\Controller;

use Api\Service\PartnerService;
use Zend\View\Model\ViewModel;

class CompanyController extends ApplicationController
{
    public function indexAction()
    {
        $menu = $this->menu;

        if (isset($menu->rows->company) and $menu->rows->company->active == 1) {
            $menuLang = $menu->locale->{$this->lang};

            $menuLangCompany = $menuLang[$menu->rows->company->id];

            $this->headTitleHelper->append($menuLangCompany->name);

            $ogFacebook = $this->openGraph->facebook();
            $ogFacebook->title = $this->headTitleHelper->renderTitle();
            $ogFacebook->description = $menuLangCompany->metaDescription;

            $this->layout()->setVariable('og', $ogFacebook);

            $viewParams = [
                'menu' => $menu,
                'lang' => $this->lang
            ];

            if ($menu->rows->{'company/colaborators'}->active == 1) {
                $viewParams['partners'] = $this->serviceManager->get(PartnerService::class)->getData($this->lang);
            }

            return (new ViewModel($viewParams));
        }

        $this->getResponse()->setStatusCode(404);
    }
}
