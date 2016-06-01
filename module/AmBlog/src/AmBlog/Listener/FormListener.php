<?php

namespace AmBlog\Listener;

use Administrator\Listener\AdministratorFormListener;
use Zend\EventManager\Event;

use AmBlog\Form\BlogFieldset;
use AmBlog\Form\BlogLocaleFieldset;

class FormListener extends AdministratorFormListener
{
    public function onCreateInitForm(Event $eventManager)
    {
        $formService = $eventManager->getParam('formService');
        $serviceLocator = $formService->getServiceLocator();

        $formService->addFieldset(BlogFieldset::class, $serviceLocator->get('AmBlog\Model\BlogTable')->getEntityModel());

        return true;
    }

    public function onUpdateInitForm(Event $eventManager)
    {
        $formService = $eventManager->getParam('formService');
        $serviceLocator = $formService->getServiceLocator();

        $tableGateway = $serviceLocator->get('AmBlog\Model\BlogTable');

        $model = $tableGateway->find($formService->getRouteParams('id'));

        $formService
            ->addFieldset(BlogFieldset::class, $model)
            ->addLocaleFieldsets(BlogLocaleFieldset::class);

        return true;
    }

    public function onCreateFormValidSuccess(Event $eventManager)
    {

    }

    public function onUpdateFormValidSuccess(Event $eventManager)
    {

    }

    public function onCreateFormValidFailed(Event $eventManager)
    {

    }

    public function onUpdateFormValidFailed(Event $eventManager)
    {

    }

    public function onCreateFormSave(Event $eventManager)
    {

    }

    public function onUpdateFormSave(Event $eventManager)
    {

    }
}