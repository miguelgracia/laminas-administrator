<?php

namespace Application\View\Helper;

use Laminas\Stdlib\ArrayObject;
use Laminas\View\Helper\AbstractHelper;

class Menu extends AbstractHelper
{
    protected $activeRouteName;

    /**
     * @param mixed $activeRouteName
     * @return Menu
     */
    public function setActiveRouteName($activeRouteName)
    {
        $this->activeRouteName = $activeRouteName;
        return $this;
    }

    public function render(ArrayObject $menu, $lang)
    {
        $url = $this->view->plugin('Url');

        foreach ($menu->rows as $routeKey => $m) {

            if (((bool) $m->active) === false) {
                continue;
            }

            $route = [$routeKey];

            $routeName = implode('/', $route);

            $link = (boolean)$m->isAnchor
                ? '#' . $routeName
                : $url('locale/' . $routeName, ['locale' => strtolower($lang)]);

            $attributes =  '';
            $class = [];

            if (strpos($this->activeRouteName, 'locale/' . $routeName)  === 0) {
                $class[] = 'active';
            }

            if((bool)$m->visible === false) {
                $class[] = 'hide';
            }

            $attributes .= 'class = "'.implode(' ', $class).'" ';

            echo sprintf(
                $this->getHtmlRow($routeName === 'contact'),
                $attributes,
                $routeKey,
                $link,
                $menu->locale->{$lang}[$m->id]->name
            );
        }
    }

    private function getHtmlRow($isButton = false)
    {
        if ($isButton) {
            return "<li %s><a data-nav-section='%s' href='%s'>%s</a></li>";
        }

        return "<li %s><a data-nav-section='%s' href='%s'><span>%s</span></a></li>";
    }
}
