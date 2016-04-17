<?php

/*
 *  Interface genérico de controlador
 *
 *  * Define un método a través del cual se producirá un set-up básico del controlador, guardando por un
 *  lado el siempre necesario service manager y por otro la localización de tablas y forms que le correspondan.
 *
 */

namespace Gestor\Controller;

interface ControllerInterface
{
    public function setControllerVars();
}