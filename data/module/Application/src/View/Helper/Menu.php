<?php

namespace Application\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\Router\Http\RouteMatch;
use Zend\Stdlib\ArrayObject;
use Zend\View\Helper\AbstractHelper;

class Menu extends AbstractHelper
{
    protected $serviceLocator;

    public function __construct(ContainerInterface $container)
    {
        $this->serviceLocator = $container;
    }

    private function getHtmlRow()
    {
        return "<li %s><a href='%s'>%s</a></li>";
    }

    public function render(ArrayObject $menu, $lang)
    {
        $routeMatch = $this->serviceLocator->get('Application')->getMvcEvent()->getRouteMatch();

        $viewHelperManager = $this->serviceLocator->get('ViewHelperManager');

        $url = $viewHelperManager->get('Url');

        foreach ($menu->rows as $routeKey => $m) {

            if ((bool) $m->active and (bool)$m->visible) {

                $route = array($routeKey);

                $routeName = implode('/', $route);

                $link = $url('locale/'.$routeName, array(
                    'locale' => strtolower($lang),
                ));

                $attributes = '';

                if ($routeMatch instanceof RouteMatch and $routeMatch->getMatchedRouteName() == $routeName) {
                    $attributes .= 'class = "active" ';
                }

                echo sprintf(
                    $this->getHtmlRow(),
                    $attributes,
                    $link,
                    $menu->locale->{$lang}[$m->id]->name
                );
            }
        }
    }
}