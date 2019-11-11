<?php

namespace AmMedia\Listener;

use Zend\EventManager\Event;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\View\Model\ViewModel;

class MediaListener implements ListenerAggregateInterface
{
    protected $listeners = array();

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents = $events->getSharedManager();

        //$this->listeners[] = $sharedEvents->attach('AmBlog\Controller\AmBlogModuleController','get.blog.edit',array($this,'onGetBlogEdit'),100);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function onGetBlogEdit(Event $eventManager)
    {
        return $this->renderUploadPluginBlock($eventManager);
    }

    private function renderUploadPluginBlock(Event $eventManager)
    {
        $serviceLocator = $eventManager->getParam('serviceLocator');

        $application = $serviceLocator->get('Application');

        $params = $application->getMvcEvent()->getRouteMatch();

        $title = 'Media Manager';
        $moduleTarget = $params->getParam('module');
        $id = $params->getParam('id');

        $viewModel = new ViewModel(compact('title','moduleTarget','id'));

        $viewModel->setTemplate('block/block');

        return $viewModel;
    }
}