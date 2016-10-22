<?php

namespace AmYouTube\Model;

use Administrator\Model\AdministratorModel;

class YouTubeModel extends AdministratorModel
{
    /**
     * @upload es un campo que hemos añadido en el formulario de subida de video
     * No es un campo que quede registrado en base de datos, por lo que lo
     * declaramos en el modelo para que al extraer los datos, este en concreto
     * sea ignorado
     */

    protected $upload;
}