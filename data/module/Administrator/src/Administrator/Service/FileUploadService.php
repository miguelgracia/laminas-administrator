<?php

namespace Gestor\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;



class FileUploadService
{

    private $config;
    private $serviceLocator;

    public function UploadFile($pathbase, $nombre, $arrayFiles)
    {
        // Primero copiamos el fichero tal cual
        $handle = fopen($arrayFiles['tmp_name'],"rw");
        if ($handle != null)
        {

            $donde_grabar = $pathbase.$nombre;
            $handle_temp = fopen($donde_grabar,"w+");
            $contenido = fread($handle, filesize($arrayFiles['tmp_name']));
            fwrite($handle_temp, $contenido);
            fclose($handle_temp);
            fclose($handle);
        } else {
            return ("Problema copiando fichero");
        }

        // Devolvemos el nombre
        return null;


    }



}
