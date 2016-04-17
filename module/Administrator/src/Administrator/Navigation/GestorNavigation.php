<?php

/**
 * Lógica extraída de:
 *
 * https://samsonasik.wordpress.com/2012/11/18/zend-framework-2-dynamic-navigation-using-zend-navigation/
 */

namespace Administrator\Navigation;


use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdministratorNavigation extends DefaultNavigationFactory
{
    protected $moduleTable;

    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {

            $application = $serviceLocator->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();
            $router      = $application->getMvcEvent()->getRouter();

            $rutaController = $routeMatch->getParam('module');

            $this->moduleTable = $serviceLocator->get('Administrator\Model\ModuleTable');

            $dataMenuTemp = $this->moduleTable->all();

            // --------- Seguridad en el pintado de men� ----------
            // antes de pintarlos vamos a ir viendo a cu�les de ellos
            // hay acceso, y por tanto, qu� opciones pintar

            $misPermisos = $serviceLocator->get('Administrator\Factory\PermisosCheckerFactory');

            // Ahora, vamos a recorrer el men� y a ver lo que es imprimible y lo que no.
            // La versi�n final va a pasar de $dataMenuTemp a $dataMenu

            $dataMenu = $misPermisos->redibujarMenu($dataMenuTemp);

            foreach ($dataMenu as $i => $menu)
            {
                // Pintamos las opciones base de men�
                $label = '<i class="fa fa-circle-thin"></i>'.$menu->module_name;

                $label .= "<i class=\"fa fa-angle-left pull-right\"></i>";
                /*if (count($menu->hijos) > 0) {

                }*/

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
//var_dump($hijo);
                        $page = array(
                            'label' => $hijo->texto,
                            'pagesWrapClass' => "treeview-menu",
                            'route' => 'administrator',
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