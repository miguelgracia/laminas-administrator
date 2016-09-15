<?php

/**
 * Lógica extraída de:
 *
 * https://samsonasik.wordpress.com/2012/11/18/zend-framework-2-dynamic-navigation-using-zend-navigation/
 */

namespace AmMenu\Navigation;


use Interop\Container\ContainerInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class MenuNavigation extends DefaultNavigationFactory
{
    protected $entradaMenuTable;

    protected function getPages(ContainerInterface $container)
    {
        if (null === $this->pages) {

            $application = $container->get('Application');

            $mvcEvent =  $application->getMvcEvent();

            $routeMatch  = $mvcEvent->getRouteMatch();
            $router      = $mvcEvent->getRouter();

            $rutaController = $routeMatch->getParam('module');
            $actionController = $routeMatch->getParam('action');

            $this->entradaMenuTable = $container->get('AmMenu\Model\MenuTable');

            $dataMenu = $this->entradaMenuTable->fetchAllOrdenados();

            // --------- Seguridad en el pintado de menú ----------
            // antes de pintarlos vamos a ir viendo a cuáles de ellos
            // hay acceso, y por tanto, qué opciones pintar

            $misPermisos = $container->get('AmProfile\Service\ProfilePermissionService');

            // Ahora, vamos a recorrer el menú y a ver lo que es imprimible y lo que no.

            $misPermisos->redibujarMenu($dataMenu);

            foreach ($dataMenu as $i => $menu)
            {
                // Pintamos las opciones base de men�
                $label = '<i class="fa fa-circle-thin"></i>'.$menu->title;

                if (count($menu->hijos) > 0) {
                    $label .= '<i class="fa fa-angle-left pull-right"></i>';
                }

                $parentMenuSection = array(
                    'label' => $label
                );

                if ($menu->action != '') {
                    $parentMenuSection['route'] = "administrator";
                    $parentMenuSection['params'] = array(
                        'module' => $menu->zendName,
                        'action' => $menu->action,
                    );
                } else {
                    $parentMenuSection['uri'] = "#";
                    if (count($menu->hijos) == 0) {
                        //Si el menú padre no contiene ningún vìnculo y además no tiene hijos, no tiene
                        //sentido pintarlo. Es más, no se debe pintar porque puede dar pistas a usuarios
                        //con menos permisos de que hay secciones que se le están ocultando.
                        continue;
                    }
                }

                // Ahora vamos a pintar sus hijos
                if (count($menu->hijos) > 0) {
                    $parentMenuSection['pages'] = array();

                    foreach ($menu->hijos as $hijo) {

                        $page = array(
                            'label' => $hijo->title,
                            'pagesWrapClass' => "treeview-menu",
                            'route' => 'administrator',
                            'params' => array(
                                'module' => $hijo->zendName,
                                'action' => $hijo->action,
                            ),
                            'active' => ($hijo->zendName == $rutaController and $actionController == $hijo->action)
                        );

                        $parentMenuSection['pages'][] = $page;
                    }
                }
                $configuration['navigation'][$this->getName()]["menu$i"] = $parentMenuSection;
            }

            if (isset($configuration['navigation'])) {
                $pages = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);

                $this->pages = $this->injectComponents($pages, $routeMatch, $router);
            }
        }

        return $this->pages;
    }
}