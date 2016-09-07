<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class ApplicationController extends AbstractActionController
{

    public function changeLanguageAction()
    {
        $config = $this->serviceLocator->get('Config');

        $session = $this->serviceLocator->get('Application\Service\SessionService');

        $lang = $this->params()->fromRoute('lang');

        if (in_array($lang, array_values($config['languages_by_host']))) {
            $session->lang = $lang;
        }

        $this->redirect()->toRoute('home');
    }
}