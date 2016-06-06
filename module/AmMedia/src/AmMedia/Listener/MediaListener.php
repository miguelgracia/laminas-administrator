<?php

namespace AmMedia\Listener;

use Zend\EventManager\Event;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\View\Model\ViewModel;

class MediaListener implements ListenerAggregateInterface
{
    protected $listeners = array();

    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

        $this->listeners[] = $sharedEvents->attach('AmBlog\Controller\AmBlogModuleController','get.blog.edit',array($this,'onGetBlogEdit'),100);
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

        $viewModel = new ViewModel(array(
            'title' => 'Media Manager',
        ));

        $viewModel->setTemplate('block/block');

        return $viewModel;
    }
}