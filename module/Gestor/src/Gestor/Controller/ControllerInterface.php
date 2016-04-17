<?php

/*
 *  Interface gen�rico de controlador
 *
 *  * Define un m�todo a trav�s del cual se producir� un set-up b�sico del controlador, guardando por un
 *  lado el siempre necesario service manager y por otro la localizaci�n de tablas y forms que le correspondan.
 *
 */

namespace Gestor\Controller;

interface ControllerInterface
{
    public function setControllerVars();
}