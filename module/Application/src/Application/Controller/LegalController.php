<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class LegalController extends ApplicationController
{
    public function indexAction()
    {
        $pageData = $this->api->staticPage->getData();

        $page = $this->getEvent()->getRouteMatch()->getParam('page');

        if (isset($pageData['locale'][$this->session->lang][$page]) and $pageData) {

            $content = $pageData['locale'][$this->session->lang][$page];

            if (isset($pageData['rows'][$content['relatedTableId']]) and ((bool)$pageData['rows'][$content['relatedTableId']]['active'])) {
                return new ViewModel(array(
                    'lang' => $this->session->lang,
                    'content' => $content
                ));
            }
        }

        $this->getResponse()->setStatusCode(404);
    }
}
