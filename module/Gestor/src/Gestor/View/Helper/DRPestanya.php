<?php
namespace Gestor\View\Helper;
use Zend\View\Helper\AbstractHelper;

class DRPestanya extends AbstractHelper
{

    // Aqui vamos a pintar las pestañas de la sección de edicion de directores de relacion
    public function __invoke($str, $id, $cuantosEquipos)
    {

        ?>
        <ul class="nav nav-tabs">

            <li<?php if ($str == 1) { echo (' class="active"'); } ?>><a href="../edit/<?php echo $id; ?>">Editar datos</a></li>
            <li<?php if ($str == 2) { echo (' class="active"'); } ?>><a href="../equipos/<?php echo $id; ?>">Editar equipos (<?php echo $cuantosEquipos; ?>)</a></li>
            <li<?php if ($str == 3) { echo (' class="active"'); } ?>><a href="../crearequipos/<?php echo $id; ?>">Crear equipo</a></li>
        </ul>

        <?php

        return;
    }

}