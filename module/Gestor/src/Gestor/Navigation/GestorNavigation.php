<?php

/**
 * Lógica extraída de:
 *
 * https://samsonasik.wordpress.com/2012/11/18/zend-framework-2-dynamic-navigation-using-zend-navigation/
 */

namespace Gestor\Navigation;


use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class GestorNavigation extends DefaultNavigationFactory
{
    protected $entradaMenuTable;

    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {

            $application = $serviceLocator->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();
            $router      = $application->getMvcEvent()->getRouter();

            $rutaController = $routeMatch->getParam('section');

            $this->entradaMenuTable = $serviceLocator->get('Gestor\Model\EntradaMenuTable');

            $dataMenuTemp = $this->entradaMenuTable->fetchAllOrdenados();

            // --------- Seguridad en el pintado de menú ----------
            // antes de pintarlos vamos a ir viendo a cuáles de ellos
            // hay acceso, y por tanto, qué opciones pintar

            $misPermisos = $serviceLocator->get('Gestor\Factory\PermisosCheckerFactory');

            // Ahora, vamos a recorrer el menú y a ver lo que es imprimible y lo que no.
            // La versión final va a pasar de $dataMenuTemp a $dataMenu

            $dataMenu = $misPermisos->RedibujarMenu($dataMenuTemp);

            foreach ($dataMenu as $i => $menu)
            {
                // Pintamos las opciones base de menú
                $label = '<i class="fa fa-circle-thin"></i>'.$menu->texto;

                if (count($menu->hijos) > 0) {
                    $label .= "<i class=\"fa fa-angle-left pull-right\"></i>";
                }

                $parentMenuSection = array(
                    'label' => $label
                );

                if ($menu->tieneEnlace) {
                    $parentMenuSection['route'] = "mastah/sections";
                    $parentMenuSection['params'] = array(
                        'section' => $menu->nombreZend,
                        'action' => $menu->accion,
                    );
                } else {
                    $parentMenuSection['uri'] = "#";
                }

                // Ahora vamos a pintar sus hijos
                if (count($menu->hijos) > 0) {
                    $parentMenuSection['pages'] = array();

                    foreach ($menu->hijos as $hijo) {
//var_dump($hijo);
                        $page = array(
                            'label' => $hijo->texto,
                            'pagesWrapClass' => "treeview-menu",
                            'route' => 'mastah/sections',
                            'params' => array(
                                'section' => $hijo->nombreZend,
                                'action' => $hijo->accion,
                            ),
                            'active' => $hijo->nombreZend == $rutaController
                        );

                        $parentMenuSection['pages'][] = $page;
                    }
                }
                $configuration['navigation'][$this->getName()]["menu$i"] = $parentMenuSection;
            }

            if (!isset($configuration['navigation'])) {
                throw new \InvalidArgumentException('Could not find navigation configuration key');
            }
            if (!isset($configuration['navigation'][$this->getName()])) {
                throw new \InvalidArgumentException(sprintf(
                    'Failed to find a navigation container by the name "%s"',
                    $this->getName()
                ));
            }

            $pages = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);

            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }

        return $this->pages;
    }
}