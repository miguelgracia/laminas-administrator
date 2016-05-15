<?php

/**
 * Lógica extraída de:
 *
 * https://samsonasik.wordpress.com/2012/11/18/zend-framework-2-dynamic-navigation-using-zend-navigation/
 */

namespace AmMenu\Navigation;


use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class MenuNavigation extends DefaultNavigationFactory
{
    protected $entradaMenuTable;

    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {

            $application = $serviceLocator->get('Application');

            $mvcEvent =  $application->getMvcEvent();

            $routeMatch  = $mvcEvent->getRouteMatch();
            $router      = $mvcEvent->getRouter();

            $rutaController = $routeMatch->getParam('module');
            $actionController = $routeMatch->getParam('action');

            $this->entradaMenuTable = $serviceLocator->get('AmMenu\Model\MenuTable');

            $dataMenu = $this->entradaMenuTable->fetchAllOrdenados();

            // --------- Seguridad en el pintado de men� ----------
            // antes de pintarlos vamos a ir viendo a cu�les de ellos
            // hay acceso, y por tanto, qu� opciones pintar

            $misPermisos = $serviceLocator->get('Administrator\Factory\PermisosCheckerFactory');

            // Ahora, vamos a recorrer el men� y a ver lo que es imprimible y lo que no.
            // La versi�n final va a pasar de $dataMenuTemp a $dataMenu

            $misPermisos->redibujarMenu($dataMenu);

            foreach ($dataMenu as $i => $menu)
            {
                // Pintamos las opciones base de men�
                $label = '<i class="fa fa-circle-thin"></i>'.$menu->texto;

                if (count($menu->hijos) > 0) {
                    $label .= '<i class="fa fa-angle-left pull-right"></i>';
                }

                $parentMenuSection = array(
                    'label' => $label
                );

                if ($menu->tieneEnlace) {
                    $parentMenuSection['route'] = "administrator";
                    $parentMenuSection['params'] = array(
                        'module' => $menu->nombreZend,
                        'action' => $menu->accion,
                    );
                } else {
                    $parentMenuSection['uri'] = "#";
                }

                // Ahora vamos a pintar sus hijos
                if (count($menu->hijos) > 0) {
                    $parentMenuSection['pages'] = array();

                    foreach ($menu->hijos as $hijo) {

                        $page = array(
                            'label' => $hijo->texto,
                            'pagesWrapClass' => "treeview-menu",
                            'route' => 'administrator',
                            'params' => array(
                                'module' => $hijo->nombreZend,
                                'action' => $hijo->accion,
                            ),
                            'active' => ($hijo->nombreZend == $rutaController and $actionController == $hijo->accion)
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