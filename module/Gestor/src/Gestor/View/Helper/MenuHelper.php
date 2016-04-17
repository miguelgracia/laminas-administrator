<?php
namespace Gestor\View\Helper;

use Zend\View\Helper\AbstractHelper;

class MenuHelper extends AbstractHelper
{
    protected $entradaMenuTable;
    protected $serviceLocator;

    function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator->getServiceLocator();
    }

    public function __invoke($viewManager)
    {
        $rutaController = $viewManager
            ->getServiceLocator()
            ->get('application')
            ->getMvcEvent()
            ->getRouteMatch()
            ->getParam('section');

        $texto = '';

        if (!$this->entradaMenuTable) {

            $config = $this->serviceLocator->get('Config');
            $basepath = $config['basepath_gestor'];

            $this->entradaMenuTable = $this->serviceLocator->get('Gestor\Model\EntradaMenuTable');

            $dataMenuTemp = $this->entradaMenuTable->fetchAllOrdenados();

            // --------- Seguridad en el pintado de menú ----------
            // antes de pintarlos vamos a ir viendo a cuáles de ellos
            // hay acceso, y por tanto, qué opciones pintar


            $misPermisos = $this->serviceLocator->get('Gestor\Factory\PermisosCheckerFactory');

            // Ahora, vamos a recorrer el menú y a ver lo que es imprimible y lo que no.
            // La versión final va a pasar de $dataMenuTemp a $dataMenu

            $dataMenu = $misPermisos->RedibujarMenu($dataMenuTemp);

            // Pintamos
            echo "<ul class='sidebar-menu'>";
            foreach ($dataMenu as $i => $menu)
            {
                // En el <li> que abre un menu, tiene que poner también class=active si está dentro
                $activo = 0;
                foreach ($menu->hijos as $hijo)
                {
                    if ($hijo->nombreZend == $rutaController)
                    {
                        $activo = 1;
                        break;
                    }
                }

                $liClass = $activo == 1 ? 'active ' : '';
                $liClass .= count($menu->hijos) > 0 ? 'treeview' : '';

                echo "<li class='$liClass'>";

                // Pintamos las opciones base de menú
                $href = $menu->tieneEnlace == 1 ? "/".$basepath."/".$menu->nombreZend : "#";

                echo "<a href='$href'>";
                echo "<i class=\"fa fa-circle-thin\"></i>";
                echo "<span>" . $menu->texto . "</span>";
                if (count($menu->hijos) > 0) {
                    echo "<i class=\"fa fa-angle-left pull-right\"></i>";
                }
                echo "</a>";

                // Ahora vamos a pintar sus hijos
                if (count($menu->hijos) > 0)
                {
                    echo '<ul class="treeview-menu '.($activo ? 'menu-open' : '').'" style="'.($activo ? 'display:block' : '').'">';
                    foreach ($menu->hijos as $hijo) {
                        if ($hijo->tieneEnlace == 1) {
                            // Este es el enlace del hijo, pero también tenemos que detectar si está active!!!

                            echo ($hijo->nombreZend == $rutaController)
                                ? "<li class='active'>"
                                : "<li>";

                            echo "<a href='/".$basepath."/".$hijo->nombreZend."'>";
                            echo $hijo->texto;
                            echo "</a>";
                        } else {
                            echo "<li>";
                            echo "<span>";
                            echo $hijo->texto;
                            echo "</span>";
                        }
                        echo "</li>";
                    }
                    echo "</ul>";
                }

                echo "</li>";
            }
            echo "</ul>";
        }
        return $texto;
    }
}