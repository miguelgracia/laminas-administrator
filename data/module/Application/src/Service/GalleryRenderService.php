<?php

namespace Application\Service;

use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\AggregateResolver;
use Zend\View\Resolver\TemplateMapResolver;

class GalleryRenderService
{
    protected $view;

    protected $renderer;

    public function __construct($options = [])
    {

        $resolver = (new AggregateResolver)->attach(new TemplateMapResolver([
            'layout'      => __DIR__ . '/../../view/layout/gallery-pagination-layout.phtml',
            'pagination' => __DIR__ . '/../../view/application/gallery/index.phtml',
        ]));

        $this->renderer = $options['ViewRenderer'];

        $this->renderer->setResolver($resolver);

        $this->view = (new ViewModel)->setTemplate('pagination');
    }

    public function render($variables = [])
    {
        $this->view->setVariables($variables);

        return $this->renderer->render($this->view);
    }
}
