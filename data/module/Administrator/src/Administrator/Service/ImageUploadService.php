<?php

namespace Gestor\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class ImageUploadService
{
    private $config;
    private $serviceLocator;

    // Genera un codigo de 10 digitos con el idFile para la foto
    private function GenerarCodigo($idFile, $length) // $length es la longitud del codigo
    {
        $source = 'abcdefghjkmnpqrstuvwxyz';
        $source .= 'ABCDEFGHJKMNPQRSTUVWXYZ';
        $source .= '123456789';
        if ($length > 0) {
            $rstr = '';
            $source = str_split($source, 1);

            for ($i = 1; $i <= $length; $i++) {
                echo mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1, count($source));
                $rstr .= $source[$num - 1];
            }
        }

        $cadena = $idFile . '_' . $rstr; // Montamos la cadena, única gracias a que contamos con el id.
        return $cadena;
    }

    public function UploadImage($pathbase, $maxWidth, $maxHeight, $arrayFiles, $idFile)
    {
        // Primero copiamos el fichero tal cual
        $handle = fopen($arrayFiles['tmp_name'], 'rw');
        if ($handle != null) {
            //$nombre1 = $idFile.'.'; // Esto es lo que vamos a cambiar, y generamos un código aleatorio
            $nombre1 = $this->GenerarCodigo($idFile, 10) . '.';

            switch ($arrayFiles['type']) {
                case 'image/jpeg':
                case 'image/pjpeg':
                    $nombre1 = $nombre1 . 'jpg';
                    break;
                case 'image/png':
                    $nombre1 = $nombre1 . 'png';
                    break;
                case 'image/gif':
                    $nombre1 = $nombre1 . 'gif';
                    break;
                default:
                    return -1;
                    break;
            }

            $donde_grabar = $pathbase . $nombre1;
            $handle_temp = fopen($donde_grabar, 'w+');
            $contenido = fread($handle, filesize($arrayFiles['tmp_name']));
            fwrite($handle_temp, $contenido);
            fclose($handle_temp);
            fclose($handle);
        }

        // Ahora va el thumbnail
        // Grabacion base
        $nombre_thumb = substr($nombre1, 0, strlen($nombre1) - 4) . '_thumb' . substr($nombre1, (strlen($nombre1) - 4), strlen($nombre1));
        // Ahora si
        $donde_t2 = $pathbase . $nombre_thumb;
        $handle_t2 = fopen($donde_t2, 'w+');
        fwrite($handle_t2, $contenido);
        fclose($handle_t2);
        $imagedata2 = getimagesize($donde_t2);

        $w = $imagedata2[0];
        $h = $imagedata2[1];

        if (($w <= $maxWidth) &&
            ($h <= $maxHeight)) {
            $new_w_t2 = $w;
            $new_h_t2 = $h;
        } else {
            $new_w_t2 = $maxWidth;
            if ((($new_w_t2 * $h) / $w) > $maxHeight) {
                $new_w_t2 = (($maxHeight * $w) / $h);
            }
            $new_h_t2 = ($new_w_t2 * $h) / $w;   // Asi nos sale equilibrada
        }

        $image_temp_t2 = ImageCreateTrueColor($new_w_t2, $new_h_t2);   // Tenemos una nueva del tama?o deseado
        if (($arrayFiles['type'] == 'image/jpeg') || ($arrayFiles['type'] == 'image/pjpeg')) {
            $image_t2 = ImageCreateFromJpeg($donde_t2);
        } elseif ($arrayFiles['type'] == 'image/gif') {
            $image_t2 = ImageCreateFromGif($donde_t2);
        } elseif ($arrayFiles['type'] == 'image/png') {
            $image_t2 = ImageCreateFromPng($donde_t2);
        }

        imagecopyResampled($image_temp_t2, $image_t2, 0, 0, 0, 0, $new_w_t2, $new_h_t2, $imagedata2[0], $imagedata2[1]);

        if (($arrayFiles['type'] == 'image/jpeg') || ($arrayFiles['type'] == 'image/pjpeg')) {
            $resultado_t2 = imagejpeg($image_temp_t2, $donde_t2, 95);
        } elseif ($arrayFiles['type'] == 'image/gif') {
            $resultado_t2 = imagegif($image_temp_t2, $donde_t2);
        } elseif ($arrayFiles['type'] == 'image/png') {
            $resultado_t2 = imagepng($image_temp_t2, $donde_t2);
        }

        // Devolvemos el nombre
        return $nombre1;
    }
}
