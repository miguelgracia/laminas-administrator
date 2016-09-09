<?php

namespace Application\View\Helper;

use Zend\Mvc\Router\Http\RouteMatch;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class Menu extends AbstractHelper implements FactoryInterface
{
    protected $serviceLocator;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    private function getHtmlRow()
    {
        return "<li %s><a href='%s'>%s</a></li>";
    }

    public function render($menu, $lang)
    {
        $serviceManager = $this->serviceLocator->getServiceLocator();

        $routeMatch = $serviceManager->get('Application')->getMvcEvent()->getRouteMatch();

        $url = $this->serviceLocator->get('Url');

        foreach ($menu->rows as $routeKey => $m) {

            if ((bool) $m['active'] and (bool)$m['visible']) {

                $route = array($lang, $routeKey);

                $urlKey = $menu->locale->{$lang}[$m['id']]['urlKey'];

                $link = $url(implode('/', $route), array(
                    'lang' => strtolower($lang),
                    'section' => $urlKey
                ));

                $attributes = '';

                if ($routeMatch instanceof RouteMatch) {
                    if (strcasecmp($routeMatch->getParam('section'),$urlKey) === 0) {
                        $attributes .= 'class = "active" ';
                    }
                }

                echo sprintf(
                    $this->getHtmlRow(),
                    $attributes,
                    $link,
                    $menu->locale->{$lang}[$m['id']]['name']
                );
            }
        }
    }
}