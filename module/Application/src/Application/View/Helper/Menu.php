<?php

namespace Application\View\Helper;

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
        return "<li><a href='%s'>%s</a></li>";
    }

    public function render($menu, $lang)
    {
        $url = $this->serviceLocator->get('Url');

        foreach ($menu->rows as $routeKey => $m) {

            if ((bool) $m['active'] and (bool)$m['visible']) {

                $route = array($lang, $routeKey);

                $link = $url(implode('/', $route), array(
                    'lang' => strtolower($lang),
                    'section' => $menu->locale->{$lang}[$m['id']]['urlKey']
                ));

                echo sprintf(
                    $this->getHtmlRow(),
                    $link,
                    $menu->locale->{$lang}[$m['id']]['name']
                );
            }
        }
    }
}