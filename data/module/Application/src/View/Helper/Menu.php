<?php

namespace Application\View\Helper;

use Zend\Stdlib\ArrayObject;
use Zend\View\Helper\AbstractHelper;

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

            if (((bool) $m->active) === false or ((bool)$m->visible) === false) {
                continue;
            }

            $route = [$routeKey];

            $routeName = implode('/', $route);

            $link = $url('locale/' . $routeName, [
                'locale' => strtolower($lang),
            ]);

            $attributes = '';

            if (strpos($this->activeRouteName, 'locale/' . $routeName)  === 0) {
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

    private function getHtmlRow()
    {
        return "<li %s><a href='%s'>%s</a></li>";
    }
}
