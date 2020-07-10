<?php

/**
 * Lógica extraída de:
 *
 * https://samsonasik.wordpress.com/2012/11/18/zend-framework-2-dynamic-navigation-using-zend-navigation/
 */

namespace AmMenu\Navigation;

use AmMenu\Model\MenuTable;
use AmProfile\Service\ProfilePermissionService;
use Interop\Container\ContainerInterface;
use Laminas\Navigation\Service\DefaultNavigationFactory;

class MenuNavigation extends DefaultNavigationFactory
{
    protected function getPages(ContainerInterface $container)
    {
        if (!is_null($this->pages)) {
            return $this->pages;
        }

        $mvcEvent = $container->get('Application')->getMvcEvent();

        $routeMatch = $mvcEvent->getRouteMatch();

        $dataMenu = $container->get(MenuTable::class)->fetchAllOrdenados();

        /**
         * --------- Seguridad en el pintado de menú ----------
         * antes de pintarlos vamos a ir viendo a cuáles de ellos
         * hay acceso, y por tanto, qué opciones pintar
         */

        $misPermisos = $container->get(ProfilePermissionService::class);

        // Ahora, vamos a recorrer el menú y a ver lo que es imprimible y lo que no.

        $misPermisos->redibujarMenu($dataMenu);

        $configuration = [];
        foreach ($dataMenu as $i => $menu) {
            // Pintamos las opciones base de menú
            $label = '<i class="fa fa-circle-thin"></i>' . $menu->title;

            if (count($menu->hijos) > 0) {
                $label .= '<i class="fa fa-angle-left pull-right"></i>';
            }

            $parentMenuSection = [
                'label' => $label
            ];

            if ($menu->action != '') {
                $parentMenuSection['route'] = 'administrator';
                $parentMenuSection['params'] = [
                    'module' => $menu->zendName,
                    'action' => $menu->action,
                ];
            } elseif (count($menu->hijos) == 0) {
                //Si el menú padre no contiene ningún vìnculo y además no tiene hijos, no tiene
                //sentido pintarlo. Es más, no se debe pintar porque puede dar pistas a usuarios
                //con menos permisos de que hay secciones que se le están ocultando.
                continue;
            }

            $parentMenuSection['uri'] = '#';

            if (count($menu->hijos) == 0) {
                $configuration['navigation'][$this->getName()]["menu$i"] = $parentMenuSection;
                continue;
            }

            // Ahora vamos a pintar sus hijos
            $parentMenuSection['pages'] = [];

            foreach ($menu->hijos as $hijo) {
                $parentMenuSection['pages'][] = [
                    'label' => $hijo->title,
                    'pagesWrapClass' => 'treeview-menu',
                    'route' => 'administrator',
                    'params' => [
                        'module' => $hijo->zendName,
                        'action' => $hijo->action,
                    ],
                    'active' => (
                        $hijo->zendName == $routeMatch->getParam('module') and
                        $hijo->action == $routeMatch->getParam('action')
                    )
                ];
            }
            $configuration['navigation'][$this->getName()]["menu$i"] = $parentMenuSection;
        }

        if (isset($configuration['navigation'])) {
            $pages = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
            $this->pages = $this->injectComponents($pages, $routeMatch, $mvcEvent->getRouter());
        }

        return $this->pages;
    }
}
